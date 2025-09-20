<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\User;
use App\Models\InterviewSession;
use App\Models\MasteryTopic;
use App\Models\CheatSheet;
use App\Models\CompanyBrief;
use App\Models\UserDocument;
use App\Models\AiPersona;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Get dashboard overview data
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Get basic statistics
        $stats = $this->getBasicStats($user);

        // Get recent interview sessions
        $recentSessions = $this->getRecentSessions($user);

        // Get mastery topics that need work
        $masteryTopics = $this->getMasteryOverview($user);

        // Get job postings
        $jobPostings = JobPosting::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get user documents
        $documents = UserDocument::where('user_id', $user->id)
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent cheat sheets
        $cheatSheets = CheatSheet::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recent_sessions' => $recentSessions,
            'mastery_topics' => $masteryTopics,
            'job_postings' => $jobPostings,
            'documents' => $documents,
            'cheat_sheets' => $cheatSheets,
        ]);
    }

    /**
     * Get detailed statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        $user = Auth::user();
        $period = $request->get('period', '30'); // days

        $startDate = Carbon::now()->subDays((int)$period);

        $stats = [
            'interviews' => $this->getInterviewStats($user, $startDate),
            'skills' => $this->getSkillsAnalysis($user, $startDate),
            'performance' => $this->getPerformanceAnalysis($user, $startDate),
            'time_analysis' => $this->getTimeAnalysis($user, $startDate)
        ];

        return response()->json($stats);
    }

    /**
     * Get activity timeline
     */
    public function activity(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $limit = $request->get('limit', 20);

        $activities = collect();

        // Get recent interviews
        $interviews = $user->interviews()
            ->with(['jobPosting:id,title,company'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($interview) {
                return [
                    'type' => 'interview',
                    'action' => $interview->status === 'completed' ? 'completed' : 'started',
                    'title' => "Interview for {$interview->jobPosting->title} at {$interview->jobPosting->company}",
                    'date' => $interview->updated_at,
                    'data' => [
                        'interview_id' => $interview->id,
                        'status' => $interview->status,
                        'score' => $interview->overall_score
                    ]
                ];
            });

        // Get recent job postings
        /** @var User $user */
        $jobPostings = $user->jobPostings()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($jobPosting) {
                return [
                    'type' => 'job_posting',
                    'action' => 'uploaded',
                    'title' => "Job posting: {$jobPosting->title} at {$jobPosting->company}",
                    'date' => $jobPosting->created_at,
                    'data' => [
                        'job_posting_id' => $jobPosting->id
                    ]
                ];
            });

        // Get recent resumes
        /** @var User $user */
        $resumes = $user->resumes()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($resume) {
                return [
                    'type' => 'resume',
                    'action' => 'uploaded',
                    'title' => "Resume: {$resume->title}",
                    'date' => $resume->created_at,
                    'data' => [
                        'resume_id' => $resume->id,
                        'is_primary' => $resume->is_primary
                    ]
                ];
            });

        // Merge and sort activities
        $activities = $activities
            ->merge($interviews)
            ->merge($jobPostings)
            ->merge($resumes)
            ->sortByDesc('date')
            ->take($limit)
            ->values();

        return response()->json($activities);
    }

    /**
     * Get basic statistics
     */
    private function getBasicStats(User $user): array
    {
        return [
            'total_sessions' => $user->interviewSessions()->count(),
            'completed_sessions' => $user->interviewSessions()->where('status', 'completed')->count(),
            'average_score' => $user->interviewSessions()
                ->where('status', 'completed')
                ->whereNotNull('overall_score')
                ->avg('overall_score') ?? 0,
            'total_job_postings' => $user->jobPostings()->count(),
            'total_documents' => $user->userDocuments()->count(),
            'total_cheat_sheets' => $user->cheatSheets()->count(),
            'mastery_average' => $user->masteryTopics()->avg('mastery_level') ?? 0,
            'this_week_sessions' => $user->interviewSessions()
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->count(),
        'practice_streak' => $this->calculatePracticeStreak($user),
        ];
    }

    /**
     * Get recent interview sessions
     */
    private function getRecentSessions(User $user, int $limit = 5): array
    {
        return $user->interviewSessions()
            ->with(['jobPosting:id,title,company', 'aiPersona:id,name,interview_style'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'session_type' => $session->session_type,
                    'focus_area' => $session->focus_area,
                    'difficulty' => $session->difficulty,
                    'job_posting' => $session->jobPosting ? [
                        'id' => $session->jobPosting->id,
                        'title' => $session->jobPosting->title,
                        'company' => $session->jobPosting->company
                    ] : null,
                    'ai_persona' => $session->aiPersona ? [
                        'id' => $session->aiPersona->id,
                    'name' => $session->aiPersona->name,
                        'interview_style' => $session->aiPersona->interview_style
                    ] : null,
                    'status' => $session->status,
                    'overall_score' => $session->overall_score,
                    'created_at' => $session->created_at,
                    'duration_minutes' => $session->duration_minutes
                ];
            })
            ->toArray();
    }

    /**
     * Get mastery overview
     */
    private function getMasteryOverview(User $user): array
    {
        $topics = $user->masteryTopics()
            ->orderBy('mastery_level', 'asc')
    ->orderBy('priority', 'desc')
            ->get();

        return [
            'needs_work' => $topics->where('mastery_level', '<', 60)->take(5)->values()->toArray(),
            'strong_areas' => $topics->where('mastery_level', '>=', 80)->take(5)->values()->toArray(),
            'categories' => $topics->groupBy('category')->map(function ($categoryTopics) {
                return [
                'average_mastery' => $categoryTopics->avg('mastery_level'),
                    'count' => $categoryTopics->count(),
                    'needs_work_count' => $categoryTopics->where('mastery_level', '<', 60)->count()
                ];
            })->toArray()
        ];
    }

    /**
     * Calculate practice streak

    private function calculatePracticeStreak(User $user): int

        $sessions = $user->interviewSessions()
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($session) {
                return $session->created_at->format('Y-m-d');
            });

        $streak = 0;
        $currentDate = Carbon::now();

        while ($sessions->has($currentDate->format('Y-m-d'))) {
            $streak++;
            $currentDate->subDay();
        }

        return $streak;
    }

    /**
et progress data
     */
    private function getProgressData(User $user): array
    {
        $completedInterviews = $user->interviews()
            ->where('status', 'completed')
            ->whereNotNull('overall_score')
        ->orderBy('created_at')
            ->get(['overall_score', 'created_at']);

        $progressPoints = $completedInterviews->map(function ($interview, $index) {
            return [
                'interview_number' => $index + 1,
                'score' => $interview->overall_score,
        'date' => $interview->created_at->format('Y-m-d')
            ];
        });

        return [
            'score_progression' => $progressPoints->toArray(),
            'improvement_rate' => $this->calculateImprovementRate($completedInterviews),
            'best_score' => $completedInterviews->max('overall_score') ?? 0,
            'latest_score' => $completedInterviews->last()->overall_score ?? 0
    ];
    }

    /**
     * Get performance trends
     */
    private function getPerformanceTrends(User $user): array
    {
        $last30Days = $user->interviews()
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
    ->get();

$skillsPerformance = [];

        foreach ($last30Days as $interview) {
            if ($interview->skill_scores) {
                foreach ($interview->skill_scores as $skill => $score) {
                    if (!isset($skillsPerformance[$skill])) {
                        $skillsPerformance[$skill] = [];
                    }
                    $skillsPerformance[$skill][] = $score;
                }
            }
}

        // Calculate average scores for each skill
        $skillAverages = [];
        foreach ($skillsPerformance as $skill => $scores) {
            $skillAverages[$skill] = [
                'average' => array_sum($scores) / count($scores),
                'count' => count($scores),
                'trend' => $this->calculateTrend($scores)
            ];
        }

        return [
            'skills_performance' => $skillAverages,
        'weekly_activity' => $this->getWeeklyActivity($user),
            'completion_rate' => $this->getCompletionRate($user)
        ];
    }

    /**
     * Get interview statistics
     */
    private function getInterviewStats(User $user, Carbon $startDate): array
    {
        $interviews = $user->interviews()->where('created_at', '>=', $startDate);

        return [
    'total' => $interviews->count(),
            'completed' => $interviews->where('status', 'completed')->count(),
            'in_progress' => $interviews->where('status', 'in_progress')->count(),
            'average_duration' => $interviews->where('status', 'completed')->avg('duration_minutes') ?? 0,
            'average_score' => $interviews->where('status', 'completed')->avg('overall_score') ?? 0
        ];
    }

    /**
 * Get skills analysis
     */
    private function getSkillsAnalysis(User $user, Carbon $startDate): array
    {
        $interviews = $user->interviews()
            ->where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('skill_scores')
            ->get();

        $skillsData = [];

        foreach ($interviews as $interview) {
            if ($interview->skill_scores) {
        foreach ($interview->skill_scores as $skill => $score) {
                    if (!isset($skillsData[$skill])) {
                        $skillsData[$skill] = [];
                    }
                    $skillsData[$skill][] = $score;
                }
            }
        }

        $skillsAnalysis = [];
        foreach ($skillsData as $skill => $scores) {
            $skillsAnalysis[$skill] = [
                'average' => array_sum($scores) / count($scores),
                'best' => max($scores),
                'worst' => min($scores),
                'count' => count($scores),
                'improvement' => $this->calculateTrend($scores)
    ];
        }

return $skillsAnalysis;
    }

    /**
et performance analysis
     */
    private function getPerformanceAnalysis(User $user, Carbon $startDate): array
{
        $interviews = $user->interviews()
            ->where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at')
            ->get();

        $scores = $interviews->pluck('overall_score')->filter()->toArray();

return [
            'average_score' => !empty($scores) ? array_sum($scores) / count($scores) : 0,
            'best_score' => !empty($scores) ? max($scores) : 0,
    'worst_score' => !empty($scores) ? min($scores) : 0,
            'improvement_trend' => $this->calculateTrend($scores),
            'consistency' => $this->calculateConsistency($scores)
];
    }

    /**
     * Get time analysis
     */
    private function getTimeAnalysis(User $user, Carbon $startDate): array
    {
        $interviews = $user->interviews()
            ->where('created_at', '>=', $startDate)
            ->get();

        $durations = $interviews->where('status', 'completed')
            ->pluck('duration_minutes')
            ->filter()
            ->toArray();

        return [
    'average_duration' => !empty($durations) ? array_sum($durations) / count($durations) : 0,
            'total_time_spent' => array_sum($durations),
            'shortest_interview' => !empty($durations) ? min($durations) : 0,
            'longest_interview' => !empty($durations) ? max($durations) : 0

    }

    /**
late improvement rate
     */
    private function calculateImprovementRate($interviews): float
    {
        if ($interviews->count() < 2) {
            return 0;
}

        $first = $interviews->first()->overall_score ?? 0;
    $last = $interviews->last()->overall_score ?? 0;

        if ($first == 0) {
            return 0;
        }

        return (($last - $first) / $first) * 100;
    }

    /**
     * Calculate trend for a series of values
 */
    private function calculateTrend(array $values): string
    {
        if (count($values) < 2) {
            return 'stable';
        }

        $first = array_slice($values, 0, ceil(count($values) / 2));
        $second = array_slice($values, floor(count($values) / 2));

        $firstAvg = array_sum($first) / count($first);
        $secondAvg = array_sum($second) / count($second);

        $difference = $secondAvg - $firstAvg;

        if ($difference > 5) {
    return 'improving';
        } elseif ($difference < -5) {
            return 'declining';
        } else {
            return 'stable';
        }
    }

    /**
     * Get weekly activity
     */
    private function getWeeklyActivity(User $user): array
    {
        $weeks = [];

        for ($i = 6; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $count = $user->interviews()
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $weeks[] = [
                'week' => $startOfWeek->format('M d'),
                'count' => $count
            ];
        }

        return $weeks;
    }

    /**
     * Get completion rate
     */
    private function getCompletionRate(User $user): float
    {
        $total = $user->interviews()->count();
        $completed = $user->interviews()->where('status', 'completed')->count();

        return $total > 0 ? ($completed / $total) * 100 : 0;
    }

    /**
     * Calculate consistency score
     */
    private function calculateConsistency(array $scores): float
    {
        if (count($scores) < 2) {
            return 100;
        }

        $mean = array_sum($scores) / count($scores);
        $variance = array_sum(array_map(function($score) use ($mean) {
            return pow($score - $mean, 2);
        }, $scores)) / count($scores);

        $standardDeviation = sqrt($variance);

        // Convert to consistency percentage (lower deviation = higher consistency)
        return max(0, 100 - ($standardDeviation * 2));
    }
}
