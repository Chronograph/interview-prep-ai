<?php

namespace App\Livewire;

use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;
    public $stats;
    public $recent_interviews;
    public $job_postings;
    public $resumes;
    
    public $activeTab = 'overview';
    public $showInterviewInterface = false;
    public $selectedJobPosting = null;
    public $selectedInterview = null;

    public function mount($user, $stats, $recent_interviews, $job_postings, $resumes)
    {
        $this->user = $user;
        $this->stats = $stats;
        $this->recent_interviews = $recent_interviews;
        $this->job_postings = $job_postings;
        $this->resumes = $resumes;
    }

    public function getCompletionRateProperty()
    {
        if ($this->stats['total_interviews'] === 0) return 0;
        return round(($this->stats['completed_interviews'] / $this->stats['total_interviews']) * 100);
    }

    public function getPrimaryResumeProperty()
    {
        return collect($this->resumes)->firstWhere('is_primary', true);
    }

    public function getRecentJobPostingsProperty()
    {
        return collect($this->job_postings)
            ->filter(fn($jobPosting) => $jobPosting && isset($jobPosting['title']))
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
                'status' => 'in_progress'
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

    public function refreshData()
    {
        // Refresh the data by redirecting to the same page
        return redirect()->route('dashboard');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getScoreColor($score)
    {
        if ($score >= 80) return 'text-green-600';
        if ($score >= 60) return 'text-yellow-600';
        return 'text-red-600';
    }

    public function getScoreBadgeColor($score)
    {
        if ($score >= 80) return 'bg-green-100 text-green-800';
        if ($score >= 60) return 'bg-yellow-100 text-yellow-800';
        return 'bg-red-100 text-red-800';
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
