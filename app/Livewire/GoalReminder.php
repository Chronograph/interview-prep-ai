<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\InterviewSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GoalReminder extends Component
{
    public $compact = false; // For smaller display versions

    public function mount($compact = false)
    {
        $this->compact = $compact;
    }

    public function getGoalRemindersProperty()
    {
        $user = Auth::user();
        if (! $user) {
            return [];
        }

        $settings = $user->getSettings();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        // Calculate applications this week
        $applicationsThisWeek = Application::where('user_id', $user->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->count();

        // Calculate practice sessions this week
        $sessionsThisWeek = InterviewSession::where('user_id', $user->id)
            ->where('is_practice', true)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->count();

        $reminders = [];

        // Application reminders
        if ($applicationsThisWeek < $settings->job_applications_per_week) {
            $remaining = $settings->job_applications_per_week - $applicationsThisWeek;
            $reminders[] = [
                'type' => 'applications',
                'message' => $this->compact
                    ? "Need {$remaining} more applications"
                    : "You need {$remaining} more job applications this week to reach your goal.",
                'icon' => 'briefcase',
                'color' => 'blue',
                'action' => 'Apply to jobs',
                'action_url' => route('analytics.applications.index'),
                'progress' => round(($applicationsThisWeek / $settings->job_applications_per_week) * 100),
            ];
        }

        // Practice session reminders
        if ($sessionsThisWeek < $settings->practice_interviews_per_week) {
            $remaining = $settings->practice_interviews_per_week - $sessionsThisWeek;
            $reminders[] = [
                'type' => 'practice',
                'message' => $this->compact
                    ? "Need {$remaining} more practice sessions"
                    : "You need {$remaining} more practice sessions this week to reach your goal.",
                'icon' => 'video-camera',
                'color' => 'orange',
                'action' => 'Start practice',
                'action_url' => route('practice.sessions'),
                'progress' => round(($sessionsThisWeek / $settings->practice_interviews_per_week) * 100),
            ];
        }

        return $reminders;
    }

    public function render()
    {
        return view('livewire.goal-reminder');
    }
}
