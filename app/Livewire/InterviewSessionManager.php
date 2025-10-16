<?php

namespace App\Livewire;

use App\Models\AiPersona;
use App\Models\JobPosting;
use App\Services\AIService;
use App\Services\InterviewPracticeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
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

    public $difficulty = 'intermediate';

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

    public $aiPersonas = [];

    protected InterviewPracticeService $practiceService;

    protected AIService $aiService;

    public function boot(InterviewPracticeService $practiceService, AIService $aiService)
    {
        $this->practiceService = $practiceService;
        $this->aiService = $aiService;
    }

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = Auth::user();

        $this->jobPostings = $user->jobPostings()
            ->select('id', 'title', 'company')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        $this->aiPersonas = AiPersona::active()
            ->select('id', 'name', 'interview_style', 'difficulty_level', 'department')
            ->orderBy('name')
            ->get()
            ->toArray();

        $this->sessions = $user->interviewSessions()
            ->with(['jobPosting:id,title,company', 'aiPersona:id,name,interview_style'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function startSession()
    {
        $this->validate([
            'session_type' => 'required|in:behavioral,technical,case_study',
            'focus_area' => 'required|string|max:255',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'ai_persona_id' => 'required|exists:ai_personas,id',
        ]);

        try {
            $jobPosting = $this->job_posting_id
                ? JobPosting::find($this->job_posting_id)
                : null;

            $persona = $this->ai_persona_id
                ? AiPersona::find($this->ai_persona_id)
                : null;

            $config = [
                'focus_area' => $this->focus_area,
                'difficulty' => $this->difficulty,
                'enable_recording' => false,
                'enable_video' => false,
            ];

            $this->currentSession = $this->practiceService->startSession(
                Auth::user(),
                $this->session_type,
                $jobPosting,
                $persona,
                $config
            );

            $this->loadNextQuestion();

            $this->dispatch('session-started', sessionId: $this->currentSession->id);

        } catch (\Exception $e) {
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
                $this->questionId = Str::uuid();
                $this->currentQuestion = array_merge($questionData, [
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
