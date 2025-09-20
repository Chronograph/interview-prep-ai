<?php

namespace App\Http\Controllers;

use App\Models\InterviewSession;
use App\Models\AiPersona;
use App\Models\JobPosting;
use App\Services\InterviewPracticeService;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InterviewSessionController extends Controller
{
    protected InterviewPracticeService $practiceService;
    protected AIService $aiService;

    public function __construct(InterviewPracticeService $practiceService, AIService $aiService)
    {
        $this->practiceService = $practiceService;
        $this->aiService = $aiService;
    }

    /**
     * Display a listing of interview sessions
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $sessions = $user->interviewSessions()
            ->with(['jobPosting:id,title,company', 'aiPersona:id,name,interview_style'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->session_type, function ($query, $type) {
                return $query->where('session_type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('interview-sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new interview session
     */
    public function create()
    {
        $user = Auth::user();
        
        $jobPostings = $user->jobPostings()
            ->select('id', 'title', 'company')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $aiPersonas = AiPersona::active()
            ->select('id', 'name', 'interview_style', 'difficulty', 'department')
            ->orderBy('name')
            ->get();

        return view('interview-sessions.create', compact('jobPostings', 'aiPersonas'));
    }

    /**
     * Start a new interview session
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_type' => 'required|in:behavioral,technical,case_study,mock_full',
            'focus_area' => 'required|string|max:255',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'ai_persona_id' => 'required|exists:ai_personas,id',
            'session_config' => 'nullable|array',
            'enable_recording' => 'boolean',
            'enable_video' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $session = $this->practiceService->startSession(
                Auth::user(),
                $request->only([
                    'session_type', 'focus_area', 'difficulty', 
                    'job_posting_id', 'ai_persona_id', 'session_config',
                    'enable_recording', 'enable_video'
                ])
            );

            return response()->json([
                'success' => true,
                'session' => $session->load(['aiPersona:id,name,interview_style']),
                'redirect' => route('interview-sessions.practice', $session)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start interview session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the practice interface for an interview session
     */
    public function practice(InterviewSession $session)
    {
        $this->authorize('view', $session);

        if ($session->status === 'completed') {
            return redirect()->route('interview-sessions.show', $session);
        }

        $session->load(['aiPersona', 'jobPosting']);

        return view('interview-sessions.practice', compact('session'));
    }

    /**
     * Get the next question for the interview session
     */
    public function nextQuestion(InterviewSession $session): JsonResponse
    {
        $this->authorize('update', $session);

        if ($session->status !== 'in_progress') {
            return response()->json(['error' => 'Session is not active'], 400);
        }

        try {
            $question = $this->practiceService->generateQuestion($session);
            
            return response()->json([
                'success' => true,
                'question' => $question,
                'question_number' => $session->current_question + 1,
                'total_questions' => $session->session_config['total_questions'] ?? 10
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit an answer to the current question
     */
    public function submitAnswer(Request $request, InterviewSession $session): JsonResponse
    {
        $this->authorize('update', $session);

        $validator = Validator::make($request->all(), [
            'answer' => 'required|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:10240', // 10MB max
            'video_file' => 'nullable|file|mimes:mp4,webm|max:51200', // 50MB max
            'response_time_seconds' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Handle file uploads
            $audioPath = null;
            $videoPath = null;

            if ($request->hasFile('audio_file')) {
                $audioPath = $request->file('audio_file')->store(
                    'interview-sessions/' . $session->id . '/audio',
                    'private'
                );
            }

            if ($request->hasFile('video_file')) {
                $videoPath = $request->file('video_file')->store(
                    'interview-sessions/' . $session->id . '/video',
                    'private'
                );
            }

            $result = $this->practiceService->processAnswer(
                $session,
                $request->answer,
                $audioPath,
                $videoPath,
                $request->response_time_seconds
            );

            return response()->json([
                'success' => true,
                'feedback' => $result['feedback'],
                'score' => $result['score'],
                'is_complete' => $result['is_complete'] ?? false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process answer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * End the interview session
     */
    public function end(InterviewSession $session): JsonResponse
    {
        $this->authorize('update', $session);

        try {
            $finalResults = $this->practiceService->endSession($session);

            return response()->json([
                'success' => true,
                'results' => $finalResults,
                'redirect' => route('interview-sessions.show', $session)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to end session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified interview session results
     */
    public function show(InterviewSession $session)
    {
        $this->authorize('view', $session);

        $session->load(['aiPersona', 'jobPosting']);

        return view('interview-sessions.show', compact('session'));
    }

    /**
     * Get session analytics data
     */
    public function analytics(InterviewSession $session): JsonResponse
    {
        $this->authorize('view', $session);

        $analytics = [
            'overall_score' => $session->overall_score,
            'skill_scores' => $session->skill_scores,
            'question_scores' => $session->question_scores,
            'duration_minutes' => $session->duration_minutes,
            'questions_answered' => $session->questions_answered,
            'average_response_time' => $session->average_response_time,
            'audio_quality_score' => $session->audio_quality_score,
            'video_quality_score' => $session->video_quality_score,
            'ai_feedback_summary' => $session->ai_feedback_summary,
            'improvement_suggestions' => $session->improvement_suggestions,
        ];

        return response()->json($analytics);
    }

    /**
     * Download session recording
     */
    public function downloadRecording(InterviewSession $session, string $type)
    {
        $this->authorize('view', $session);

        if (!in_array($type, ['audio', 'video'])) {
            abort(404);
        }

        $field = $type . '_recording_path';
        $path = $session->$field;

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return Storage::disk('private')->download($path);
    }

    /**
     * Delete an interview session
     */
    public function destroy(InterviewSession $session)
    {
        $this->authorize('delete', $session);

        try {
            $session->delete();

            return redirect()->route('interview-sessions.index')
                ->with('success', 'Interview session deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete session: ' . $e->getMessage());
        }
    }

    /**
     * Get available AI personas for session setup
     */
    public function getPersonas(Request $request): JsonResponse
    {
        $personas = AiPersona::active()
            ->when($request->difficulty, function ($query, $difficulty) {
                return $query->where('difficulty', $difficulty);
            })
            ->when($request->department, function ($query, $department) {
                return $query->where('department', $department);
            })
            ->select('id', 'name', 'interview_style', 'difficulty', 'department', 'description')
            ->orderBy('name')
            ->get();

        return response()->json($personas);
    }
}