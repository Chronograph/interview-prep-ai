<?php

namespace App\Livewire;

use App\Models\InterviewSession;
use App\Models\Resume;
use App\Services\AIService;
use App\Services\InterviewPracticeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Interview Practice Session')]
class EnhancedInterviewInterface extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Session data
    public $sessionId;
    public $session;
    public $currentQuestionIndex = 0;
    public $totalQuestions = 0;
    
    // Interview readiness data
    public $overallScore = 0;
    public $completedQuestions = 0;
    public $averageScore = 0;
    public $bestScore = 0;
    public $strongAreas = [];
    public $focusAreas = [];
    
    // Question navigation
    public $questions = [];
    public $currentQuestion = null;
    
    // Recording state
    public $isRecording = false;
    public $recordingTime = 0;
    public $recordedChunks = [];
    public $currentResponse = '';
    
    // Response history
    public $responseHistory = [];
    public $showHistory = false;
    
    // Modals
    public $showRetakeModal = false;
    public $showCompleteModal = false;
    
    protected AIService $aiService;
    protected InterviewPracticeService $practiceService;

    public function boot(AIService $aiService, InterviewPracticeService $practiceService)
    {
        $this->aiService = $aiService;
        $this->practiceService = $practiceService;
    }

    public function mount($sessionId = null)
    {
        if ($sessionId) {
            $this->sessionId = $sessionId;
            $this->loadSession();
        }
    }

    public function loadSession()
    {
        $this->session = InterviewSession::with(['jobPosting', 'user.resumes'])
            ->where('user_id', Auth::id())
            ->findOrFail($this->sessionId);

        $this->authorize('view', $this->session);

        // Load questions and responses
        $this->loadQuestions();
        $this->loadInterviewReadiness();
        
        // Set current question
        $this->setCurrentQuestion();
    }

    public function loadQuestions()
    {
        $this->questions = $this->session->questions_asked ?? [];
        $this->totalQuestions = count($this->questions);
        
        if ($this->totalQuestions === 0) {
            $this->generateInitialQuestions();
        }
    }

    public function loadInterviewReadiness()
    {
        $answeredQuestions = collect($this->questions)->where('answer');
        $this->completedQuestions = $answeredQuestions->count();
        
        if ($this->completedQuestions > 0) {
            $scores = $answeredQuestions->pluck('feedback.score')->filter();
            $this->averageScore = $scores->avg() ?? 0;
            $this->bestScore = $scores->max() ?? 0;
            $this->overallScore = round(($this->averageScore / 10) * 100);
            
            // Calculate strong areas and focus areas
            $this->calculateStrongAndFocusAreas($answeredQuestions);
        }
    }

    public function calculateStrongAndFocusAreas($answeredQuestions)
    {
        $categoryScores = [];
        
        foreach ($answeredQuestions as $question) {
            $category = $question['category'] ?? 'general';
            $score = $question['feedback']['score'] ?? 7;
            
            if (!isset($categoryScores[$category])) {
                $categoryScores[$category] = [];
            }
            $categoryScores[$category][] = $score;
        }
        
        $this->strongAreas = [];
        $this->focusAreas = [];
        
        foreach ($categoryScores as $category => $scores) {
            $avgScore = array_sum($scores) / count($scores);
            
            if ($avgScore >= 8) {
                $this->strongAreas[] = "Great performance on " . ucfirst(str_replace('_', ' ', $category));
            } elseif ($avgScore <= 6) {
                $this->focusAreas[] = ucfirst(str_replace('_', ' ', $category)) . " responses need improvement";
            }
        }
    }

    public function generateInitialQuestions()
    {
        try {
            // Get resume and job description for context
            $resume = $this->session->user->resumes()->latest()->first();
            $jobPosting = $this->session->jobPosting;
            
            $resumeText = $resume ? $resume->content : 'No resume available';
            $jobDescription = $jobPosting ? $jobPosting->description : 'General interview practice';
            
            // Determine number of questions based on difficulty
            $questionCount = match($this->session->difficulty_level) {
                'beginner' => 5,
                'intermediate' => 10,
                'advanced' => 15,
                default => 10
            };
            
            // Generate questions using AI
            $questions = $this->aiService->generateInterviewQuestions(
                $jobDescription,
                $resumeText,
                $this->session->session_type
            );
            
            // Limit to requested count and add metadata
            $questions = array_slice($questions, 0, $questionCount);
            $questionsWithMetadata = [];
            
            foreach ($questions as $index => $question) {
                $questionsWithMetadata[] = [
                    'id' => 'q_' . ($index + 1),
                    'question' => $question['question'],
                    'category' => $question['category'],
                    'difficulty' => $question['difficulty'],
                    'order' => $index + 1,
                    'answer' => null,
                    'feedback' => null,
                    'attempts' => [],
                    'best_score' => 0,
                    'created_at' => now()->toISOString()
                ];
            }
            
            $this->session->update(['questions_asked' => $questionsWithMetadata]);
            $this->loadQuestions();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate questions: ' . $e->getMessage());
        }
    }

    public function setCurrentQuestion($index = null)
    {
        if ($index !== null) {
            $this->currentQuestionIndex = $index;
        }
        
        if (isset($this->questions[$this->currentQuestionIndex])) {
            $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
            $this->loadResponseHistory();
        }
    }

    public function loadResponseHistory()
    {
        if ($this->currentQuestion) {
            $this->responseHistory = $this->currentQuestion['attempts'] ?? [];
        }
    }

    public function startRecording()
    {
        $this->isRecording = true;
        $this->recordingTime = 0;
        $this->recordedChunks = [];
        $this->dispatch('start-recording');
    }

    public function stopRecording()
    {
        $this->isRecording = false;
        $this->dispatch('stop-recording');
    }

    public function submitResponse()
    {
        if (empty($this->currentResponse) && empty($this->recordedChunks)) {
            session()->flash('error', 'Please provide a response before submitting.');
            return;
        }

        try {
            // Evaluate the response using AI
            $evaluation = $this->aiService->evaluateAnswer(
                $this->currentQuestion['question'],
                $this->currentResponse,
                [
                    'category' => $this->currentQuestion['category'],
                    'difficulty' => $this->currentQuestion['difficulty'],
                    'session_type' => $this->session->session_type
                ]
            );

            // Create attempt record
            $attempt = [
                'id' => 'attempt_' . time(),
                'response' => $this->currentResponse,
                'recording_chunks' => $this->recordedChunks,
                'recording_time' => $this->recordingTime,
                'evaluation' => $evaluation,
                'score' => $evaluation['score'] ?? 7,
                'submitted_at' => now()->toISOString()
            ];

            // Update question with new attempt
            $questions = $this->questions;
            $questionIndex = $this->currentQuestionIndex;
            
            if (!isset($questions[$questionIndex]['attempts'])) {
                $questions[$questionIndex]['attempts'] = [];
            }
            
            $questions[$questionIndex]['attempts'][] = $attempt;
            $questions[$questionIndex]['answer'] = $this->currentResponse;
            $questions[$questionIndex]['feedback'] = $evaluation;
            
            // Update best score
            $currentBestScore = $questions[$questionIndex]['best_score'] ?? 0;
            if ($attempt['score'] > $currentBestScore) {
                $questions[$questionIndex]['best_score'] = $attempt['score'];
            }

            // Update session
            $this->session->update(['questions_asked' => $questions]);
            
            // Reload data
            $this->loadQuestions();
            $this->loadInterviewReadiness();
            $this->loadResponseHistory();
            
            // Clear current response
            $this->currentResponse = '';
            $this->recordedChunks = [];
            $this->recordingTime = 0;
            
            session()->flash('success', 'Response submitted successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit response: ' . $e->getMessage());
        }
    }

    public function retakeQuestion()
    {
        $this->currentResponse = '';
        $this->recordedChunks = [];
        $this->recordingTime = 0;
        $this->showRetakeModal = false;
    }

    public function showQuestionHistory()
    {
        $this->showHistory = !$this->showHistory;
    }

    public function getQuestionStatus($question)
    {
        $attempts = $question['attempts'] ?? [];
        $bestScore = $question['best_score'] ?? 0;
        
        if (empty($attempts)) {
            return 'unanswered';
        }
        
        if ($bestScore >= 8) {
            return 'excellent';
        } elseif ($bestScore >= 6) {
            return 'good';
        } else {
            return 'needs_improvement';
        }
    }

    public function getQuestionStatusColor($question)
    {
        $status = $this->getQuestionStatus($question);
        
        return match($status) {
            'excellent' => 'text-green-600',
            'good' => 'text-blue-600',
            'needs_improvement' => 'text-red-600',
            default => 'text-gray-400'
        };
    }

    public function completeSession()
    {
        $this->showCompleteModal = true;
    }

    public function confirmComplete()
    {
        try {
            $this->session->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
            
            session()->flash('success', 'Interview session completed successfully!');
            return redirect()->route('practice.sessions');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to complete session: ' . $e->getMessage());
        }
    }

    public function getFormattedTimeProperty()
    {
        $minutes = floor($this->recordingTime / 60);
        $seconds = $this->recordingTime % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function render()
    {
        return view('livewire.enhanced-interview-interface');
    }
}
