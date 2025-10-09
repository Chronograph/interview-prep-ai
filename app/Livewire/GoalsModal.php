<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class GoalsModal extends Component
{
    use WireUiActions;

    public $show = false;

    public $jobApplicationsPerWeek = 5;

    public $practiceInterviewsPerWeek = 3;

    public $scoreImprovementTarget = 10;

    protected $listeners = [
        'open-goals-modal' => 'open',
    ];

    protected $rules = [
        'jobApplicationsPerWeek' => 'required|integer|min:1|max:20',
        'practiceInterviewsPerWeek' => 'required|integer|min:1|max:10',
        'scoreImprovementTarget' => 'required|integer|min:5|max:50',
    ];

    public function mount()
    {
        $this->loadCurrentGoals();
    }

    public function open()
    {
        $this->loadCurrentGoals();
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
    }

    public function saveGoals()
    {
        $this->validate();

        try {
            $user = Auth::user();
            $settings = $user->getSettings();

            $settings->update([
                'job_applications_per_week' => $this->jobApplicationsPerWeek,
                'practice_interviews_per_week' => $this->practiceInterviewsPerWeek,
                'score_improvement_target' => $this->scoreImprovementTarget,
            ]);

            $this->show = false;

            $this->dispatch('goals-updated');
            $this->notification()->success(
                'Goals Updated!',
                'Your weekly goals have been saved successfully.'
            );

        } catch (\Exception $e) {
            $this->notification()->error(
                'Error',
                'Failed to save goals. Please try again.'
            );
        }
    }

    private function loadCurrentGoals()
    {
        try {
            $user = Auth::user();
            $settings = $user->getSettings();

            $this->jobApplicationsPerWeek = $settings->job_applications_per_week ?? 5;
            $this->practiceInterviewsPerWeek = $settings->practice_interviews_per_week ?? 3;
            $this->scoreImprovementTarget = $settings->score_improvement_target ?? 10;
        } catch (\Exception $e) {
            // Use default values if settings can't be loaded
            $this->jobApplicationsPerWeek = 5;
            $this->practiceInterviewsPerWeek = 3;
            $this->scoreImprovementTarget = 10;
        }
    }

    public function render()
    {
        return view('livewire.goals-modal');
    }
}
