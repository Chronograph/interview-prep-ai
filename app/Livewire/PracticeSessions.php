<?php

namespace App\Livewire;

use App\Models\Interview;
use App\Models\InterviewSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Interview Sessions')]
class PracticeSessions extends Component
{
    public $activeTab = 'upcoming_interviews';

    // Modals
    public $showStartPracticeModal = false;

    public $showSessionTypeModal = false;

    public $selectedSessionType = null;

    public $selectedDifficulty = 'medium';

    public $selectedFocusArea = 'general';

    // Statistics
    public $totalSessions = 24;

    public $averageScore = 7.2;

    public $interviewReadyCount = 5;

    public $needPracticeCount = 3;

    public $sessionsThisWeek = 3;

    public $scoreImprovement = 18;

    // Data
    public $upcomingInterviews = [];

    public $practiceSessions = [];

    public function mount()
    {
        $this->loadData();
        $this->calculateStatistics();
    }

    public function loadData()
    {
        $this->loadUpcomingInterviews();
        $this->loadPracticeSessions();
    }

    public function loadUpcomingInterviews()
    {
        $this->upcomingInterviews = Interview::where('user_id', Auth::id())
            ->whereNotNull('started_at')
            ->where('started_at', '>=', now())
            ->orderBy('started_at')
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'date' => $interview->started_at?->toDateString(),
                    'focus_areas' => $this->getFocusAreas($interview->interview_type),
                    'practice_status' => $this->getPracticeStatus(0),
                ];
            });
    }

    public function loadPracticeSessions()
    {
        $this->practiceSessions = InterviewSession::where('user_id', Auth::id())
            ->where('is_practice', true)
            ->with(['jobPosting'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($session) {
                // Get company and role information from job posting or session config
                $company = 'Practice Session';
                $role = 'General Practice';
                
                if ($session->jobPosting) {
                    $company = $session->jobPosting->company;
                    $role = $session->jobPosting->title;
                } elseif ($session->session_config) {
                    // Check if job posting info is stored in session_config
                    $config = is_string($session->session_config) ? json_decode($session->session_config, true) : $session->session_config;
                    
                    if (isset($config['job_posting'])) {
                        $company = $config['job_posting']['company'] ?? $company;
                        $role = $config['job_posting']['title'] ?? $role;
                    } elseif (isset($config['company'])) {
                        $company = $config['company'];
                    } elseif (isset($config['role'])) {
                        $role = $config['role'];
                    }
                }
                
                return [
                    'id' => $session->id,
                    'company' => $company,
                    'role' => $role,
                    'date' => $session->created_at->format('M j, Y'),
                    'difficulty' => ucfirst($session->difficulty_level ?? 'medium'),
                    'score' => $session->overall_score ?? 0,
                    'status' => $session->status,
                    'company_initial' => strtoupper(substr($company, 0, 1)),
                ];
            });
    }

    public function calculateStatistics()
    {
        $user = Auth::user();

        // Calculate total sessions
        $this->totalSessions = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->count();

        // Calculate average score (convert from 10-point scale to 10-point scale)
        $avgScore = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->whereNotNull('overall_score')
            ->avg('overall_score');
        $this->averageScore = round(($avgScore ?? 7.2) * 10, 1); // Convert to 10-point scale

        // Calculate sessions this week
        $this->sessionsThisWeek = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        // Calculate interview ready count (interviews scheduled)
        $this->interviewReadyCount = Interview::where('user_id', $user->id)
            ->whereNotNull('started_at')
            ->where('started_at', '>=', now())
            ->count();

        // Calculate need practice count (sessions with low scores)
        $this->needPracticeCount = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->where(function($query) {
                $query->whereNull('overall_score')
                      ->orWhere('overall_score', '<', 7.0);
            })
            ->count();

        // Calculate score improvement (compare last month vs previous month)
        $lastMonth = now()->subMonth();
        $previousMonth = now()->subMonths(2);
        
        $lastMonthAvg = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->where('created_at', '>=', $lastMonth->startOfMonth())
            ->where('created_at', '<', $lastMonth->endOfMonth())
            ->whereNotNull('overall_score')
            ->avg('overall_score') ?? 0;
            
        $previousMonthAvg = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->where('created_at', '>=', $previousMonth->startOfMonth())
            ->where('created_at', '<', $previousMonth->endOfMonth())
            ->whereNotNull('overall_score')
            ->avg('overall_score') ?? 0;

        if ($previousMonthAvg > 0) {
            $this->scoreImprovement = round((($lastMonthAvg - $previousMonthAvg) / $previousMonthAvg) * 100, 1);
        } else {
            $this->scoreImprovement = 18; // Default improvement
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function startPractice($interviewId = null)
    {
        if ($interviewId) {
            return redirect()->route('interview-sessions.create', ['interview_id' => $interviewId]);
        }

        $this->showStartPracticeModal = true;
    }

    public function openSessionTypeModal($sessionType)
    {
        $this->selectedSessionType = $sessionType;
        $this->showStartPracticeModal = false;
        $this->showSessionTypeModal = true;
    }

    public function closeModals()
    {
        $this->showStartPracticeModal = false;
        $this->showSessionTypeModal = false;
        $this->selectedSessionType = null;
    }

    public function startSession()
    {
        // Map session types to appropriate configurations
        $sessionConfigs = [
            'role-specific' => [
                'session_type' => 'behavioral',
                'focus_area' => 'role_specific',
                'difficulty' => 'medium',
                'questions_count' => 10,
            ],
            'elevator-pitch' => [
                'session_type' => 'elevator_pitch',
                'focus_area' => 'communication',
                'difficulty' => 'easy',
                'questions_count' => 5,
            ],
            'company-specific' => [
                'session_type' => 'company_specific',
                'focus_area' => 'company_research',
                'difficulty' => 'hard',
                'questions_count' => 15,
            ],
            'skill-improvement' => [
                'session_type' => 'skill_focused',
                'focus_area' => $this->selectedFocusArea,
                'difficulty' => 'medium',
                'questions_count' => 10,
            ],
        ];

        $config = $sessionConfigs[$this->selectedSessionType] ?? [
            'session_type' => $this->selectedSessionType,
            'focus_area' => $this->selectedFocusArea,
            'difficulty' => $this->selectedDifficulty,
            'questions_count' => 10,
        ];

        // Create a new practice session with selected parameters
        return redirect()->route('interview-sessions.create', $config);
    }

    public function continueSession($sessionId)
    {
        // Redirect to the enhanced interview interface for the existing session
        return redirect()->route('interview-sessions.enhanced', $sessionId);
    }

    public function viewCompanySheet($interviewId)
    {
        // Redirect to company brief or cheat sheet
        return redirect()->route('cheat-sheets.index');
    }

    public function viewRoleGuide($interviewId)
    {
        // Redirect to role-specific guide
        return redirect()->route('cheat-sheets.index');
    }

    private function getFocusAreas($interviewType)
    {
        switch ($interviewType) {
            case 'mixed':
                return 'Behavioral + Product Sense';
            case 'technical':
                return 'Technical + System Design';
            case 'behavioral':
                return 'Behavioral + Leadership';
            default:
                return 'General Practice';
        }
    }

    private function getReadinessStatus($score)
    {
        if ($score >= 80) {
            return ['text' => 'Ready', 'color' => 'text-green-600'];
        } elseif ($score >= 60) {
            return ['text' => 'Almost Ready', 'color' => 'text-orange-600'];
        } else {
            return ['text' => 'Needs Practice', 'color' => 'text-orange-600'];
        }
    }

    private function getPracticeStatus($score)
    {
        if ($score >= 80) {
            return ['text' => 'Practiced', 'color' => 'text-green-600'];
        } else {
            return ['text' => 'Not Practiced', 'color' => 'text-gray-500'];
        }
    }

    public function render()
    {
        return view('livewire.practice-sessions');
    }
}
