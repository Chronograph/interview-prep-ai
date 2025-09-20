<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\JobPosting;
use App\Services\AIInterviewService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InterviewController extends Controller
{
    use AuthorizesRequests;

    protected AIInterviewService $aiService;

    public function __construct(AIInterviewService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of user's interviews
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $interviews = $user->interviews()
            ->with(['jobPosting:id,title,company'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->job_posting_id, function ($query, $jobPostingId) {
                return $query->where('job_posting_id', $jobPostingId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($interviews);
    }

    /**
     * Start a new interview session
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'job_posting_id' => 'required|exists:job_postings,id',
            'interview_type' => 'required|in:behavioral,technical,mixed',
            'duration_minutes' => 'integer|min:15|max:120',
            'difficulty_level' => 'in:beginner,intermediate,advanced'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $jobPosting = JobPosting::findOrFail($request->job_posting_id);
        $this->authorize('view', $jobPosting);

        $interview = Interview::create([
            'user_id' => Auth::id(),
            'job_posting_id' => $request->job_posting_id,
            'interview_type' => $request->interview_type,
            'duration_minutes' => $request->duration_minutes ?? 30,
            'difficulty_level' => $request->difficulty_level ?? 'intermediate',
            'status' => 'pending',
            'questions' => [],
            'responses' => [],
            'ai_feedback' => null,
            'score' => null
        ]);

        // Generate AI questions based on job posting and interview type
        try {
            $questions = $this->aiService->generateInterviewQuestions(
                $jobPosting,
                $request->interview_type,
                $request->difficulty_level ?? 'intermediate'
            );

            $interview->update(['questions' => $questions]);
        } catch (\Exception $e) {
            // Log error but continue with empty questions array
            \Log::error('Failed to generate AI questions: ' . $e->getMessage());
        }

        return response()->json($interview->load('jobPosting:id,title,company'), 201);
    }

    /**
     * Display the specified interview
     */
    public function show(Interview $interview): JsonResponse
    {
        $this->authorize('view', $interview);
        
        return response()->json(
            $interview->load(['jobPosting:id,title,company', 'user:id,name,email'])
        );
    }

    /**
     * Start recording an interview session
     */
    public function startRecording(Interview $interview): JsonResponse
    {
        $this->authorize('update', $interview);

        if ($interview->status !== 'pending') {
            return response()->json([
                'message' => 'Interview must be in pending status to start recording'
            ], 422);
        }

        $interview->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return response()->json([
            'message' => 'Interview recording started',
            'interview' => $interview
        ]);
    }

    /**
     * Stop recording and process the interview
     */
    public function stopRecording(Request $request, Interview $interview): JsonResponse
    {
        $this->authorize('update', $interview);

        if ($interview->status !== 'in_progress') {
            return response()->json([
                'message' => 'Interview must be in progress to stop recording'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|integer',
            'responses.*.answer' => 'required|string',
            'responses.*.duration_seconds' => 'integer|min:1'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $interview->update([
            'status' => 'completed',
            'completed_at' => now(),
            'responses' => $request->responses
        ]);

        // Generate AI feedback asynchronously
        try {
            $feedback = $this->aiService->generateFeedback(
                $interview->questions,
                $request->responses,
                $interview->jobPosting
            );

            $interview->update([
                'ai_feedback' => $feedback,
                'score' => $feedback['overall_score'] ?? null
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to generate AI feedback: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Interview completed successfully',
            'interview' => $interview->fresh()
        ]);
    }

    /**
     * Upload interview recording files
     */
    public function uploadRecording(Request $request, Interview $interview): JsonResponse
    {
        $this->authorize('update', $interview);

        $validator = Validator::make($request->all(), [
            'video' => 'file|mimes:mp4,webm,mov|max:102400', // 100MB max
            'audio' => 'file|mimes:mp3,wav,m4a|max:51200'    // 50MB max
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $recordings = [];

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store(
                'interviews/' . $interview->id . '/recordings',
                'private'
            );
            $recordings['video_path'] = $videoPath;
        }

        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store(
                'interviews/' . $interview->id . '/recordings',
                'private'
            );
            $recordings['audio_path'] = $audioPath;
        }

        if (!empty($recordings)) {
            $interview->update(['recordings' => array_merge(
                $interview->recordings ?? [],
                $recordings
            )]);
        }

        return response()->json([
            'message' => 'Recording uploaded successfully',
            'recordings' => $recordings
        ]);
    }

    /**
     * Get interview feedback and analysis
     */
    public function getFeedback(Interview $interview): JsonResponse
    {
        $this->authorize('view', $interview);

        if ($interview->status !== 'completed') {
            return response()->json([
                'message' => 'Interview must be completed to view feedback'
            ], 422);
        }

        return response()->json([
            'feedback' => $interview->ai_feedback,
            'score' => $interview->score,
            'questions' => $interview->questions,
            'responses' => $interview->responses
        ]);
    }

    /**
     * Delete an interview
     */
    public function destroy(Interview $interview): JsonResponse
    {
        $this->authorize('delete', $interview);

        // Delete associated recording files
        if ($interview->recordings) {
            foreach ($interview->recordings as $path) {
                if (Storage::disk('private')->exists($path)) {
                    Storage::disk('private')->delete($path);
                }
            }
        }

        $interview->delete();

        return response()->json([
            'message' => 'Interview deleted successfully'
        ]);
    }

    /**
     * Get interview statistics for dashboard
     */
    public function statistics(): JsonResponse
    {
        $user = Auth::user();
        
        $stats = [
            'total_interviews' => $user->interviews()->count(),
            'completed_interviews' => $user->interviews()->where('status', 'completed')->count(),
            'average_score' => $user->interviews()
                ->whereNotNull('score')
                ->avg('score'),
            'recent_interviews' => $user->interviews()
                ->with('jobPosting:id,title,company')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'performance_trend' => $this->getPerformanceTrend($user)
        ];

        return response()->json($stats);
    }

    /**
     * Get performance trend data
     */
    private function getPerformanceTrend($user): array
    {
        $interviews = $user->interviews()
            ->where('status', 'completed')
            ->whereNotNull('score')
            ->orderBy('completed_at')
            ->get(['score', 'completed_at']);

        return $interviews->map(function ($interview) {
            return [
                'score' => $interview->score,
                'date' => $interview->completed_at->format('Y-m-d')
            ];
        })->toArray();
    }
}
