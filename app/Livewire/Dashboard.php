<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\Interview;
use App\Models\InterviewSession;
use App\Models\JobPosting;
use App\Models\MasteryScore;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $user;

    public $stats;

    public $recent_interviews;

    public $job_postings;

    public $resumes;

    public $activeTab = 'recent';

    public $showInterviewInterface = false;

    public $selectedJobPosting = null;

    public $selectedInterview = null;

    // New properties for the redesigned dashboard
    public $practiceSessions;

    public $upcomingInterviews;

    public $audioVisualSetup;

    public $recommendations;

    public $skillProgress;

    public $recommendedJobs;

    public $productInsights;

    // Statistics properties
    public $totalSessions;

    public $averageScore;

    public $interviewReadyCount;

    public $needPracticeCount;

    public $sessionsThisWeek;

    public $scoreImprovement;

    public function mount($user = null, $stats = null, $recent_interviews = null, $job_postings = null, $resumes = null)
    {
        $this->user = $user ?? Auth::user();

        // Redirect to login if user is not authenticated
        if (! $this->user) {
            return redirect()->route('login');
        }

        $this->stats = $stats ?? [];
        $this->recent_interviews = $recent_interviews ?? collect();
        $this->job_postings = $job_postings ?? collect();
        $this->resumes = $resumes ?? collect();

        // Initialize new data structures
        $this->initializeDashboardData();
        $this->calculateStatistics();
    }

    private function initializeDashboardData()
    {
        // Load practice sessions from database
        $this->practiceSessions = $this->loadPracticeSessions();

        $this->upcomingInterviews = $this->loadUpcomingInterviews();

        $this->audioVisualSetup = $this->loadAudioVisualSetup();

        $this->recommendations = $this->loadRecommendations();

        $this->skillProgress = $this->loadSkillProgress();

        $this->recommendedJobs = $this->loadRecommendedJobs();

        $this->productInsights = $this->loadProductInsights();
    }

    private function loadPracticeSessions()
    {
        return InterviewSession::where('user_id', $this->user->id)
            ->where('is_practice', true)
            ->with(['jobPosting', 'aiPersona'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($session) {
                $company = $session->jobPosting?->company ?? 'Practice Session';
                $role = $session->jobPosting?->title ?? 'General Practice';

                return [
                    'id' => $session->id,
                    'company' => $company,
                    'role' => $role,
                    'difficulty' => ucfirst($session->difficulty_level ?? 'medium'),
                    'time_ago' => $session->created_at->diffForHumans(),
                    'questions_answered' => $session->questions_completed.'/'.$session->questions_planned,
                    'score' => $session->overall_score,
                    'status' => $session->status,
                    'company_initial' => strtoupper(substr($company, 0, 1)),
                ];
            });
    }

    private function loadUpcomingInterviews()
    {
        return Application::where('user_id', $this->user->id)
            ->whereIn('status', ['applied', 'phone_screen', 'interview_scheduled'])
            ->where('expected_response_date', '>=', now())
            ->orderBy('expected_response_date', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($application) {
                $date = $application->expected_response_date;
                $formattedDate = $date ? $date->format('M j') : 'TBD';
                $formattedTime = $date ? $date->format('g:i A') : 'TBD';

                return [
                    'id' => $application->id,
                    'company' => $application->company_name,
                    'role' => $application->position_title,
                    'date' => $formattedDate,
                    'time' => $formattedTime,
                    'type' => ucfirst(str_replace('_', ' ', $application->status)),
                ];
            });
    }

    private function loadAudioVisualSetup()
    {
        $latestSession = InterviewSession::where('user_id', $this->user->id)
            ->whereNotNull('video_quality')
            ->whereNotNull('audio_quality')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestSession) {
            return [
                'video_quality' => $latestSession->video_quality ?? 75,
                'audio_quality' => $latestSession->audio_quality ?? 75,
                'background_quality' => $latestSession->background_quality ?? 75,
                'distracting_elements' => $latestSession->has_background_noise ? 65 : 85,
            ];
        }

        // Default values if no session data available
        return [
            'video_quality' => 75,
            'audio_quality' => 75,
            'background_quality' => 75,
            'distracting_elements' => 75,
        ];
    }

    private function loadRecommendations()
    {
        $userSkills = $this->user->skills ?? [];
        $weakAreas = MasteryScore::where('user_id', $this->user->id)
            ->where('score', '<', 7.0)
            ->orderBy('score', 'asc')
            ->limit(3)
            ->get();

        $recommendations = collect();

        // Generate recommendations based on weak areas
        foreach ($weakAreas as $weakArea) {
            $recommendations->push([
                'title' => 'Improve '.ucfirst($weakArea->skill),
                'priority' => $weakArea->score < 5.0 ? 'high' : 'medium',
                'description' => 'Focus on '.strtolower($weakArea->skill).' skills',
                'duration' => '20 min',
                'category' => ucfirst($weakArea->skill),
                'icon' => $this->getSkillIcon($weakArea->skill),
            ]);
        }

        // Add general recommendations if we don't have enough
        if ($recommendations->count() < 4) {
            $generalRecommendations = [
                [
                    'title' => 'Practice Behavioral Questions',
                    'priority' => 'high',
                    'description' => 'Focus on STAR method, structured storytelling',
                    'duration' => '20 min',
                    'category' => 'Behavioral',
                    'icon' => 'star',
                ],
                [
                    'title' => 'Develop a Stronger Elevator Pitch',
                    'priority' => 'medium',
                    'description' => 'Create compelling introductions',
                    'duration' => '15 min',
                    'category' => 'Communication',
                    'icon' => 'microphone',
                ],
            ];

            foreach ($generalRecommendations as $rec) {
                if ($recommendations->count() < 4) {
                    $recommendations->push($rec);
                }
            }
        }

        return $recommendations->take(4);
    }

    private function loadSkillProgress()
    {
        $scores = MasteryScore::where('user_id', $this->user->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('skill');

        $labels = [];
        $overall = [];
        $productThinking = [];
        $communication = [];
        $leadership = [];

        // Generate progress data for the last 5 sessions
        $sessionCount = 0;
        foreach ($scores as $skill => $skillScores) {
            $sessionCount++;
            if ($sessionCount > 5) {
                break;
            }

            $labels[] = "Session {$sessionCount}";
            $overall[] = $skillScores->avg('score') ?? 5.0;

            // Map skills to categories
            if (in_array($skill, ['product_strategy', 'user_research', 'data_analysis'])) {
                $productThinking[] = $skillScores->avg('score') ?? 5.0;
            } elseif (in_array($skill, ['communication', 'presentation', 'storytelling'])) {
                $communication[] = $skillScores->avg('score') ?? 5.0;
            } elseif (in_array($skill, ['leadership', 'stakeholder_management', 'team_management'])) {
                $leadership[] = $skillScores->avg('score') ?? 5.0;
            }
        }

        // Fill in missing data points with default values
        while (count($labels) < 5) {
            $sessionNum = count($labels) + 1;
            $labels[] = "Session {$sessionNum}";
            $overall[] = 5.0;
            $productThinking[] = 5.0;
            $communication[] = 5.0;
            $leadership[] = 5.0;
        }

        return [
            'labels' => $labels,
            'overall' => $overall,
            'product_thinking' => $productThinking,
            'communication' => $communication,
            'leadership' => $leadership,
        ];
    }

    private function loadRecommendedJobs()
    {
        return JobPosting::where('user_id', $this->user->id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($job) {
                $userSkills = $this->user->skills ?? [];
                $jobSkills = $job->skills ?? [];
                $matchPercentage = $this->calculateJobMatch($userSkills, $jobSkills);

                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'company' => $job->company,
                    'location' => $job->location,
                    'match' => $matchPercentage,
                    'posted' => $job->created_at->diffForHumans(),
                    'applicants' => rand(20, 100), // This would come from external job board API
                    'salary' => $this->formatSalaryRange($job->salary_min, $job->salary_max),
                    'practice_score' => $this->calculatePracticeScore($job),
                    'readiness' => $this->calculateReadiness($matchPercentage),
                    'strong_skills' => array_slice($jobSkills, 0, 2),
                    'practice_areas' => $this->getPracticeAreas($userSkills, $jobSkills),
                ];
            });
    }

    private function loadProductInsights()
    {
        $avgScore = MasteryScore::where('user_id', $this->user->id)->avg('score') ?? 5.0;
        $topSkill = MasteryScore::where('user_id', $this->user->id)
            ->orderBy('score', 'desc')
            ->first();
        $weakSkill = MasteryScore::where('user_id', $this->user->id)
            ->orderBy('score', 'asc')
            ->first();

        $message = 'Your overall performance is '.$this->getPerformanceLevel($avgScore).' ';
        if ($topSkill) {
            $message .= 'with strong '.str_replace('_', ' ', $topSkill->skill).' skills. ';
        }
        if ($weakSkill && $weakSkill->score < 6.0) {
            $message .= 'Focus on improving '.str_replace('_', ' ', $weakSkill->skill).' to unlock better opportunities.';
        } else {
            $message .= 'Continue practicing to maintain your strong performance.';
        }

        return [
            'message' => $message,
            'action' => 'Get Job Alerts',
        ];
    }

    private function getSkillIcon($skill)
    {
        $icons = [
            'communication' => 'microphone',
            'leadership' => 'star',
            'product_strategy' => 'lightbulb',
            'user_research' => 'users',
            'data_analysis' => 'chart-bar',
            'stakeholder_management' => 'handshake',
            'presentation' => 'presentation',
            'storytelling' => 'book',
            'team_management' => 'users',
        ];

        return $icons[$skill] ?? 'star';
    }

    private function calculateJobMatch($userSkills, $jobSkills)
    {
        if (empty($userSkills) || empty($jobSkills)) {
            return 50; // Default match if no skills data
        }

        $matchingSkills = array_intersect($userSkills, $jobSkills);
        $matchPercentage = (count($matchingSkills) / count($jobSkills)) * 100;

        return min(100, max(0, round($matchPercentage)));
    }

    private function formatSalaryRange($min, $max)
    {
        if (! $min && ! $max) {
            return 'Salary not specified';
        }

        if ($min && $max) {
            return '$'.number_format($min / 1000).'k - $'.number_format($max / 1000).'k';
        }

        return $min ? '$'.number_format($min / 1000).'k+' : 'Up to $'.number_format($max / 1000).'k';
    }

    private function calculatePracticeScore($job)
    {
        // This would be calculated based on user's performance in similar roles
        return round(rand(60, 95) / 10, 1);
    }

    private function calculateReadiness($matchPercentage)
    {
        if ($matchPercentage >= 80) {
            return 'High';
        }
        if ($matchPercentage >= 60) {
            return 'Medium';
        }

        return 'Low';
    }

    private function getPracticeAreas($userSkills, $jobSkills)
    {
        $missingSkills = array_diff($jobSkills, $userSkills);

        return array_slice($missingSkills, 0, 3);
    }

    private function getPerformanceLevel($score)
    {
        if ($score >= 8.0) {
            return 'excellent';
        }
        if ($score >= 6.5) {
            return 'good';
        }
        if ($score >= 5.0) {
            return 'average';
        }

        return 'needs improvement';
    }

    private function calculateStatistics()
    {
        // Calculate total sessions
        $this->totalSessions = InterviewSession::where('user_id', $this->user->id)
            ->where('is_practice', true)
            ->count();

        // Calculate average score
        $avgScore = InterviewSession::where('user_id', $this->user->id)
            ->where('is_practice', true)
            ->whereNotNull('overall_score')
            ->avg('overall_score');
        $this->averageScore = $avgScore ? round($avgScore, 1) : 0;

        // Calculate sessions this week
        $this->sessionsThisWeek = InterviewSession::where('user_id', $this->user->id)
            ->where('is_practice', true)
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        // Calculate score improvement (compare last 2 weeks vs previous 2 weeks)
        $recentScores = InterviewSession::where('user_id', $this->user->id)
            ->where('is_practice', true)
            ->whereNotNull('overall_score')
            ->where('created_at', '>=', now()->subWeeks(2))
            ->where('created_at', '<', now()->subWeek())
            ->avg('overall_score');

        $olderScores = InterviewSession::where('user_id', $this->user->id)
            ->where('is_practice', true)
            ->whereNotNull('overall_score')
            ->where('created_at', '>=', now()->subWeeks(4))
            ->where('created_at', '<', now()->subWeeks(2))
            ->avg('overall_score');

        if ($olderScores && $recentScores) {
            $this->scoreImprovement = round((($recentScores - $olderScores) / $olderScores) * 100);
        } else {
            $this->scoreImprovement = 0;
        }

        // Calculate interview ready companies (applications with high readiness)
        $this->interviewReadyCount = Application::where('user_id', $this->user->id)
            ->whereIn('status', ['applied', 'phone_screen', 'interview_scheduled'])
            ->where('expected_response_date', '>=', now())
            ->count();

        // Calculate companies needing practice (based on low mastery scores)
        $lowScoreSkills = MasteryScore::where('user_id', $this->user->id)
            ->where('score', '<', 6.0)
            ->count();
        $this->needPracticeCount = max(0, $lowScoreSkills);
    }

    public function getFormattedScoreImprovementProperty()
    {
        if ($this->scoreImprovement > 0) {
            return '+'.$this->scoreImprovement.'%';
        } elseif ($this->scoreImprovement < 0) {
            return $this->scoreImprovement.'%';
        }

        return '0%';
    }

    public function getScoreImprovementColorProperty()
    {
        if ($this->scoreImprovement > 0) {
            return 'text-green-600';
        } elseif ($this->scoreImprovement < 0) {
            return 'text-red-600';
        }

        return 'text-gray-600';
    }

    public function getSessionsThisWeekTextProperty()
    {
        if ($this->sessionsThisWeek > 0) {
            return '+'.$this->sessionsThisWeek.' this week';
        }

        return 'No sessions this week';
    }

    public function refreshData()
    {
        $this->calculateStatistics();
        $this->initializeDashboardData();
    }

    public function getCompletionRateProperty()
    {
        if ($this->stats['total_interviews'] === 0) {
            return 0;
        }

        return round(($this->stats['completed_interviews'] / $this->stats['total_interviews']) * 100);
    }

    public function getPrimaryResumeProperty()
    {
        return collect($this->resumes)->firstWhere('is_primary', true);
    }

    public function getRecentJobPostingsProperty()
    {
        return collect($this->job_postings)
            ->filter(fn ($jobPosting) => $jobPosting && isset($jobPosting['title']))
            ->take(3);
    }

    public function startInterview($jobPostingId = null)
    {
        try {
            $jobPosting = $jobPostingId ? JobPosting::find($jobPostingId) : null;
            $primaryResume = $this->primaryResume;

            $interview = Interview::create([
                'user_id' => Auth::id(),
                'job_posting_id' => $jobPosting?->id,
                'resume_id' => $primaryResume?->id ?? null,
                'status' => 'in_progress',
            ]);

            $this->selectedInterview = $interview;
            $this->selectedJobPosting = $jobPosting;
            $this->showInterviewInterface = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to start interview. Please try again.');
        }
    }

    public function closeInterview()
    {
        $this->showInterviewInterface = false;
        $this->selectedJobPosting = null;
        $this->selectedInterview = null;
        $this->refreshData();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getScoreColor($score)
    {
        if ($score >= 80) {
            return 'text-green-600';
        }
        if ($score >= 60) {
            return 'text-yellow-600';
        }

        return 'text-red-600';
    }

    public function getScoreBadgeColor($score)
    {
        if ($score >= 80) {
            return 'bg-green-100 text-green-800';
        }
        if ($score >= 60) {
            return 'bg-yellow-100 text-yellow-800';
        }

        return 'bg-red-100 text-red-800';
    }

    public function getDifficultyColor($difficulty)
    {
        switch ($difficulty) {
            case 'Hard':
                return 'bg-red-100 text-red-800';
            case 'Medium':
                return 'bg-yellow-100 text-yellow-800';
            case 'Easy':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getReadinessColor($readiness)
    {
        switch ($readiness) {
            case 'High':
                return 'bg-green-100 text-green-800';
            case 'Medium':
                return 'bg-yellow-100 text-yellow-800';
            case 'Low':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getPriorityColor($priority)
    {
        switch ($priority) {
            case 'high':
                return 'text-red-600';
            case 'medium':
                return 'text-yellow-600';
            case 'low':
                return 'text-green-600';
            default:
                return 'text-gray-600';
        }
    }

    public function getProgressBarColor($value, $isNegative = false)
    {
        if ($isNegative) {
            if ($value >= 80) {
                return 'bg-red-500';
            }
            if ($value >= 60) {
                return 'bg-yellow-500';
            }

            return 'bg-orange-500';
        }

        if ($value >= 90) {
            return 'bg-green-500';
        }
        if ($value >= 70) {
            return 'bg-blue-500';
        }

        return 'bg-gray-500';
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
