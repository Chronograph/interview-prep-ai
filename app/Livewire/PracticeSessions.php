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
                return [
                    'id' => $session->id,
                    'company' => $session->jobPosting?->company ?? 'Practice Session',
                    'role' => $session->jobPosting?->title ?? 'General Practice',
                    'date' => $session->created_at->format('M j, Y'),
                    'difficulty' => ucfirst($session->difficulty_level ?? 'medium'),
                    'score' => $session->overall_score ?? 0,
                    'status' => $session->status,
                    'company_initial' => strtoupper(substr($session->jobPosting?->company ?? 'P', 0, 1)),
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

        // Calculate average score
        $avgScore = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->whereNotNull('overall_score')
            ->avg('overall_score');
        $this->averageScore = round($avgScore ?? 7.2, 1);

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

        // Calculate need practice count (interviews scheduled)
        $this->needPracticeCount = Interview::where('user_id', $user->id)
            ->whereNotNull('started_at')
            ->where('started_at', '>=', now())
            ->count();

        // Mock score improvement calculation
        $this->scoreImprovement = 18; // This would be calculated based on historical data
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
        // Create a new practice session with selected parameters
        return redirect()->route('interview-sessions.create', [
            'session_type' => $this->selectedSessionType,
            'difficulty' => $this->selectedDifficulty,
            'focus_area' => $this->selectedFocusArea,
        ]);
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
