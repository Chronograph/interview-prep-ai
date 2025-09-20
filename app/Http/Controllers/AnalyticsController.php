<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MasteryScore;
use App\Models\TopicProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        return response()->json([
            'masteryScores' => $this->getMasteryScores($user),
            'topicProgress' => $this->getTopicProgress($user),
            'applicationStats' => $this->getApplicationStats($user),
            'overallStats' => $this->getOverallStats($user),
        ]);
    }

    public function getMasteryData(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $topic = $request->get('topic');
        
        $query = $user->masteryScores();
        
        if ($topic) {
            $query->where('topic', $topic);
        }
        
        return response()->json([
            'mastery_scores' => $query->get(),
            'average_score' => $query->avg('score') ?? 0,
            'total_attempts' => $query->sum('attempts'),
        ]);
    }

    public function getTopicProgressData(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $category = $request->get('category');
        
        $query = $user->topicProgress();
        
        if ($category) {
            $query->where('category', $category);
        }
        
        return response()->json([
            'progress' => $query->get(),
            'categories' => $user->topicProgress()->distinct('category')->pluck('category'),
        ]);
    }

    public function getApplicationData(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $status = $request->get('status');
        
        $query = $user->applications();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return response()->json([
            'applications' => $query->orderBy('application_date', 'desc')->get(),
            'status_counts' => $this->getApplicationStatusCounts($user),
        ]);
    }

    private function getMasteryScores(User $user)
    {
        return $user->masteryScores()
            ->selectRaw('topic, AVG(score) as average_score, COUNT(*) as skill_count')
            ->groupBy('topic')
            ->orderBy('average_score', 'desc')
            ->get();
    }

    private function getTopicProgress(User $user)
    {
        return $user->topicProgress()
            ->orderBy('completion_percentage', 'desc')
            ->limit(10)
            ->get();
    }

    private function getApplicationStats(User $user)
    {
        return [
            'total' => $user->applications()->count(),
            'active' => $user->applications()->active()->count(),
            'interviews' => $user->applications()->whereIn('status', [
                'phone_interview', 'technical_interview', 'onsite_interview', 'final_interview'
            ])->count(),
            'offers' => $user->applications()->where('status', 'offer')->count(),
            'recent' => $user->applications()->where('application_date', '>=', now()->subDays(30))->count(),
        ];
    }

    private function getOverallStats(User $user)
    {
        return [
            'overall_mastery' => $user->getOverallMasteryScore(),
            'topics_practiced' => $user->topicProgress()->count(),
            'total_practice_time' => $user->topicProgress()->sum('time_spent_minutes'),
            'questions_attempted' => $user->topicProgress()->sum('questions_attempted'),
            'accuracy_rate' => $this->calculateOverallAccuracy($user),
        ];
    }

    private function getApplicationStatusCounts(User $user)
    {
        return $user->applications()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
    }

    private function calculateOverallAccuracy(User $user)
    {
        $totalAttempted = $user->topicProgress()->sum('questions_attempted');
        $totalCorrect = $user->topicProgress()->sum('questions_correct');
        
        return $totalAttempted > 0 ? ($totalCorrect / $totalAttempted) * 100 : 0;
    }
}
