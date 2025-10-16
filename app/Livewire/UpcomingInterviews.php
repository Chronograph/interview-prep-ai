<?php

namespace App\Livewire;

use App\Models\Interview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpcomingInterviews extends Component
{
    public $interviews = [];

    public $showCreateModal = false;

    public $editingInterview = null;

    public $showEditModal = false;

    // Form fields
    public $title = '';

    public $interview_date = '';

    public $interview_time = '';

    public $interview_type = 'mixed';

    protected $rules = [
        'title' => 'required|string|max:255',
        'interview_date' => 'required|date',
        'interview_time' => 'required',
        'interview_type' => 'required|in:behavioral,technical,mixed',
    ];

    public function mount()
    {
        $this->loadInterviews();
    }

    public function loadInterviews()
    {
        $this->interviews = Interview::where('user_id', Auth::id())
            ->whereNotNull('started_at')
            ->where('started_at', '>=', now())
            ->orderBy('started_at')
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'title' => $interview->title,
                    'interview_date' => $interview->started_at?->toDateString(),
                    'interview_time' => $interview->started_at?->format('H:i:s'),
                    'interview_type' => $interview->interview_type,
                    'status' => $interview->status,
                    'created_at' => $interview->created_at,
                ];
            });
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($interviewId)
    {
        $interview = Interview::where('user_id', Auth::id())->findOrFail($interviewId);

        $this->editingInterview = $interview;
        $this->title = $interview->title;
        $this->interview_date = $interview->started_at?->toDateString() ?? '';
        $this->interview_time = $interview->started_at?->format('H:i') ?? '';
        $this->interview_type = $interview->interview_type;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingInterview = null;
        $this->resetForm();
    }

    public function createInterview()
    {
        $this->validate();

        $startedAt = Carbon::parse($this->interview_date.' '.$this->interview_time);

        Interview::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'interview_type' => $this->interview_type,
            'started_at' => $startedAt,
            'status' => 'pending',
        ]);

        $this->loadInterviews();
        $this->closeCreateModal();

        session()->flash('message', 'Interview scheduled successfully!');
    }

    public function updateInterview()
    {
        $this->validate();

        $startedAt = Carbon::parse($this->interview_date.' '.$this->interview_time);

        $this->editingInterview->update([
            'title' => $this->title,
            'interview_type' => $this->interview_type,
            'started_at' => $startedAt,
        ]);

        $this->loadInterviews();
        $this->closeEditModal();

        session()->flash('message', 'Interview updated successfully!');
    }

    public function deleteInterview($interviewId)
    {
        $interview = Interview::where('user_id', Auth::id())->findOrFail($interviewId);
        $interview->delete();

        $this->loadInterviews();
        session()->flash('message', 'Interview deleted successfully!');
    }

    public function startPractice($interviewId)
    {
        // Redirect to practice session with interview context
        return redirect()->route('practice', ['interview_id' => $interviewId]);
    }

    private function resetForm()
    {
        $this->title = '';
        $this->interview_date = '';
        $this->interview_time = '';
        $this->interview_type = 'mixed';
    }

    public function render()
    {
        return view('livewire.upcoming-interviews');
    }
}
