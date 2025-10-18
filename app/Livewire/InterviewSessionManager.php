<?php

namespace App\Livewire;

use App\Models\AiPersona;
use App\Models\InterviewSession;
use App\Models\JobPosting;
use App\Services\AIService;
use App\Services\InterviewPracticeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Interview Session Manager')]
class InterviewSessionManager extends Component
{
    use AuthorizesRequests;

    // Form properties for session creation
    public $session_type = 'behavioral';

    public $focus_area = 'General Interview Skills';

    public $difficulty = 'medium';

    public $job_posting_id;

    public $ai_persona_id;

    // Session management
    public $currentSession;

    public $sessions;

    // Question management
    public $currentQuestion = [];

    public $questionId = '';

    public $questionNumber = 1;

    public $totalQuestions = 10;

    // Answer handling
    public $userAnswer = '';

    public $feedback = '';

    public $isSubmitted = false;

    // Data for dropdowns
    public $jobPostings = [];

    protected InterviewPracticeService $practiceService;

    protected AIService $aiService;

    public function boot(InterviewPracticeService $practiceService, AIService $aiService)
    {
        $this->practiceService = $practiceService;
        $this->aiService = $aiService;
    }

    public function mount($job_posting_id = null, $resume_id = null, $session_type = null, $difficulty = null, $questions_count = null)
    {
        // Extract parameters from request (Livewire doesn't auto-map URL params to method params)
        $request = request();
        $job_posting_id = $job_posting_id ?: $request->get('job_posting_id');
        $resume_id = $resume_id ?: $request->get('resume_id');
        $session_type = $session_type ?: $request->get('session_type');
        $difficulty = $difficulty ?: $request->get('difficulty');
        $questions_count = $questions_count ?: $request->get('questions_count');
        
        
        // Auto-populate form fields from URL parameters
        if ($job_posting_id) {
            $this->job_posting_id = $job_posting_id;
        }
        if ($session_type) {
            $this->session_type = $session_type;
        }
        if ($difficulty) {
            $this->difficulty = $difficulty;
        }
        
        $this->loadData();
    }

    public function loadData()
    {
        try {
            $user = Auth::user();

            // Simplified data loading to avoid database timeout issues
            $this->jobPostings = [];
            
            // Load sessions without complex relationships to avoid timeout
            $this->sessions = $user->interviewSessions()
                ->select('id', 'session_type', 'difficulty_level', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5) // Reduced limit
                ->get();
                
            Log::info('Data loaded successfully', [
                'sessions_count' => $this->sessions->count()
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to load data, using empty defaults', [
                'error' => $e->getMessage()
            ]);
            $this->jobPostings = [];
            $this->sessions = collect();
        }
    }

           public function startSession()
           {
               // Set a longer timeout for testing (5 minutes)
               set_time_limit(300);
               
               try {
                   $this->validate([
                       'session_type' => 'required|in:behavioral,technical,case_study,company_specific',
                       'difficulty' => 'required|in:easy,medium,hard',
                       'job_posting_id' => 'nullable|exists:job_postings,id',
                       'ai_persona_id' => 'nullable|exists:ai_personas,id',
                   ]);
               } catch (\Illuminate\Validation\ValidationException $e) {
                   Log::error('Validation failed in startSession', [
                       'errors' => $e->errors(),
                       'current_values' => [
                           'session_type' => $this->session_type,
                           'difficulty' => $this->difficulty,
                           'job_posting_id' => $this->job_posting_id,
                           'ai_persona_id' => $this->ai_persona_id,
                       ]
                   ]);
                   throw $e;
               }

               try {
                   // Simplified session creation to avoid database timeout issues
                   Log::info('Starting simplified interview session', [
                       'session_type' => $this->session_type,
                       'difficulty' => $this->difficulty,
                       'user_id' => Auth::id()
                   ]);
                   
                   Log::info('About to create session record');

                   // Get job posting information if available
                   $jobPosting = null;
                   $sessionConfig = [];
                   
                   if ($this->job_posting_id) {
                       $jobPosting = JobPosting::find($this->job_posting_id);
                       if ($jobPosting) {
                           $sessionConfig['job_posting'] = [
                               'id' => $jobPosting->id,
                               'company' => $jobPosting->company,
                               'title' => $jobPosting->title,
                               'description' => $jobPosting->description,
                           ];
                       }
                   }
                   
                   // Create session with job posting information
                   $session = InterviewSession::create([
                       'user_id' => Auth::id(),
                       'job_posting_id' => $this->job_posting_id,
                       'session_type' => $this->session_type,
                       'difficulty_level' => $this->difficulty,
                       'is_practice' => true, // Mark as practice session
                       'status' => 'active',
                       'started_at' => now(),
                       'session_config' => $sessionConfig,
                   ]);
                   
                   Log::info('Session created successfully', [
                       'session_id' => $session->id
                   ]);
                   
                   Log::info('About to redirect to enhanced interview interface');

            // Redirect to enhanced interview interface
            return redirect()->route('interview-sessions.enhanced', $session->id);

        } catch (\Exception $e) {
            Log::error('Failed to start interview session', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_type' => $this->session_type,
                'difficulty' => $this->difficulty
            ]);
            session()->flash('error', 'Failed to start interview session: '.$e->getMessage());
        }
    }

    public function loadNextQuestion()
    {
        if (! $this->currentSession) {
            return;
        }

        try {
            $questionData = $this->practiceService->generateNextQuestion($this->currentSession);

            if ($questionData) {
                $this->questionId = (string) Str::uuid();
                $this->currentQuestion = array_merge($questionData, [
                    'question_id' => (string) Str::uuid(),
                    'asked_at' => now()->toISOString(),
                ]);
                $this->userAnswer = '';
                $this->feedback = '';
                $this->isSubmitted = false;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate question: '.$e->getMessage());
        }
    }

    public function submitAnswer()
    {
        if (! $this->currentSession || empty($this->userAnswer)) {
            return;
        }

        try {
            $result = $this->practiceService->processAnswer(
                $this->currentSession,
                $this->questionId ?: Str::uuid(),
                $this->userAnswer,
                null // audioData
            );

            $this->feedback = $result['specific_feedback'] ?? 'Thank you for your answer.';
            $this->isSubmitted = true;

            // Update question number
            $this->questionNumber++;

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process answer: '.$e->getMessage());
        }
    }

    public function endSession()
    {
        if (! $this->currentSession) {
            return;
        }

        try {
            $results = $this->practiceService->endSession($this->currentSession);

            $this->dispatch('session-completed', results: $results);
            $this->resetSession();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to end session: '.$e->getMessage());
        }
    }

    public function resetSession()
    {
        $this->currentSession = null;
        $this->currentQuestion = null;
        $this->userAnswer = '';
        $this->feedback = '';
        $this->isSubmitted = false;
        $this->questionNumber = 1;

        $this->loadData();
    }

    public function render()
    {
        return view('livewire.interview-session-manager');
    }
}
