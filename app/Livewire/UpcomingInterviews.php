<?php

namespace App\Livewire;

use App\Models\Interview;
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

    public $company = '';

    public $position = '';

    public $interview_date = '';

    public $interview_time = '';

    public $interview_type = 'video';

    public $location = '';

    public $notes = '';

    public $readiness_score = 0;

    protected $rules = [
        'title' => 'required|string|max:255',
        'company' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'interview_date' => 'required|date',
        'interview_time' => 'required',
        'interview_type' => 'required|in:behavioral,technical,mixed',
        'location' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'readiness_score' => 'required|integer|min:0|max:100',
    ];

    public function mount()
    {
        $this->loadInterviews();
    }

    public function loadInterviews()
    {
        $this->interviews = Interview::where('user_id', Auth::id())
            ->where('interview_date', '>=', now()->toDateString())
            ->orderBy('interview_date')
            ->orderBy('interview_time')
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'title' => $interview->title,
                    'company' => $interview->company,
                    'position' => $interview->position,
                    'interview_date' => $interview->interview_date,
                    'interview_time' => $interview->interview_time,
                    'interview_type' => $interview->interview_type,
                    'location' => $interview->location,
                    'notes' => $interview->notes,
                    'readiness_score' => $interview->readiness_score,
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
        $this->company = $interview->company;
        $this->position = $interview->position;
        $this->interview_date = $interview->interview_date;
        $this->interview_time = $interview->interview_time;
        $this->interview_type = $interview->interview_type;
        $this->location = $interview->location;
        $this->notes = $interview->notes;
        $this->readiness_score = $interview->readiness_score;

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

        Interview::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'company' => $this->company,
            'position' => $this->position,
            'interview_date' => $this->interview_date,
            'interview_time' => $this->interview_time,
            'interview_type' => $this->interview_type,
            'location' => $this->location,
            'notes' => $this->notes,
            'readiness_score' => $this->readiness_score,
            'status' => 'pending',
        ]);

        $this->loadInterviews();
        $this->closeCreateModal();

        session()->flash('message', 'Interview scheduled successfully!');
    }

    public function updateInterview()
    {
        $this->validate();

        $this->editingInterview->update([
            'title' => $this->title,
            'company' => $this->company,
            'position' => $this->position,
            'interview_date' => $this->interview_date,
            'interview_time' => $this->interview_time,
            'interview_type' => $this->interview_type,
            'location' => $this->location,
            'notes' => $this->notes,
            'readiness_score' => $this->readiness_score,
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
        $this->company = '';
        $this->position = '';
        $this->interview_date = '';
        $this->interview_time = '';
        $this->interview_type = 'mixed';
        $this->location = '';
        $this->notes = '';
        $this->readiness_score = 0;
    }

    public function render()
    {
        return view('livewire.upcoming-interviews');
    }
}
