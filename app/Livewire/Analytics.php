<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Analytics extends Component
{
    public $user;

    public $data;

    public function mount($user = null, $data = null)
    {
        $this->user = $user ?? Auth::user();

        if (! $data) {
            // Calculate analytics data in the expected format

            // Overall stats
            $overallMastery = $this->user->masteryTopics()->avg('mastery_level') ?? 0;
            $averageScore = $this->user->interviewSessions()->where('status', 'completed')->avg('overall_score') ?? 0;

            // Application stats
            $totalApplications = $this->user->applications()->count();
            $successfulApplications = $this->user->applications()->where('status', 'successful')->count();
            $successRate = $totalApplications > 0 ? ($successfulApplications / $totalApplications) * 100 : 0;

            // Mastery scores and topic progress - fetch actual collections
            $masteryScores = $this->user->masteryScores()->get();
            $topicProgress = $this->user->topicProgress()->get();  // use topicProgress relationship

            $data = [
                'overallStats' => [
                    'overall_mastery' => $overallMastery,
                    'average_score' => $averageScore,
                ],
                'applicationStats' => [
                    'total_applications' => $totalApplications,
                    'successful_applications' => $successfulApplications,
                    'success_rate' => $successRate,
                ],
                'masteryScores' => $masteryScores,
                'topicProgress' => $topicProgress,
                // Keep previous safe defaults but under different keys
                'total_interviews' => $this->user->interviewSessions()->count(),
                'completed_interviews' => $this->user->interviewSessions()->where('status', 'completed')->count(),
                'interview_performance' => $this->user->interviewSessions()
                    ->where('status', 'completed')
                    ->whereNotNull('overall_score')
                    ->orderBy('created_at')
                    ->get(['overall_score', 'created_at']),
            ];
        }

        $this->data = $data;
    }

    public function render()
    {
        return view('livewire.analytics');
    }
}
