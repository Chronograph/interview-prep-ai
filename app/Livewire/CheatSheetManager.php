<?php

namespace App\Livewire;

use App\Models\CheatSheet;
use App\Models\JobPosting;
use App\Models\UserDocument;
use App\Services\CheatSheetService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CheatSheetManager extends Component
{
    use WithPagination;

    public $search = '';

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public $filterByType = '';

    public $categoryFilter = '';

    public $activeTab = 'company_interviews';

    public $activeViewTab = 'company';

    // Modal states
    public $showCreateModal = false;

    public $showEditModal = false;

    public $showViewModal = false;

    public $viewingCheatSheet = null;

    // Form properties
    public $title = '';

    public $job_posting_id = '';

    public $user_document_ids = [];

    public $notes = '';

    public $interview_date = '';

    // Available options
    public $jobPostings = [];

    public $userDocuments = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'job_posting_id' => 'nullable|exists:job_postings,id',
        'user_document_ids' => 'nullable|array',
        'user_document_ids.*' => 'exists:user_documents,id',
        'notes' => 'nullable|string|max:1000',
        'interview_date' => 'nullable|date',
    ];

    public function mount()
    {
        $this->loadOptions();
    }

    public function updatingActiveTab()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $cheatSheets = CheatSheet::where('user_id', Auth::id())
            ->with(['jobPosting'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('topic_description', 'like', '%'.$this->search.'%')
                        ->orWhere('suggested_response', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($this->activeTab === 'company_interviews', function ($query) {
                $query->whereNotNull('job_posting_id');
            })
            ->when($this->activeTab === 'role_guides', function ($query) {
                $query->whereNull('job_posting_id');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);

        $stats = $this->getStats();

        return view('livewire.cheat-sheet-manager', [
            'cheatSheets' => $cheatSheets,
            'stats' => $stats,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingFilterByType()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openViewModal($cheatSheetId)
    {
        $this->viewingCheatSheet = CheatSheet::where('user_id', Auth::id())
            ->with(['jobPosting'])
            ->findOrFail($cheatSheetId);
        $this->showViewModal = true;
        $this->activeViewTab = 'company'; // Reset to company tab when opening modal
    }

    public function openEditModal($cheatSheetId)
    {
        $this->viewingCheatSheet = CheatSheet::where('user_id', Auth::id())
            ->with(['jobPosting'])
            ->findOrFail($cheatSheetId);
        $this->title = $this->viewingCheatSheet->title;
        $this->job_posting_id = $this->viewingCheatSheet->job_posting_id;
        $this->notes = $this->viewingCheatSheet->topic_description;
        $this->showEditModal = true;
    }

    public function viewCheatSheet($cheatSheetId)
    {
        $this->openViewModal($cheatSheetId);
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showViewModal = false;
        $this->viewingCheatSheet = null;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        try {
            $cheatSheetService = app(CheatSheetService::class);
            $user = Auth::user();
            $jobPosting = $this->job_posting_id ? JobPosting::find($this->job_posting_id) : null;

            // Generate a category based on the title or use a default
            $category = 'custom';
            $topicDescription = $this->notes ?: "Custom cheat sheet: {$this->title}";

            $cheatSheet = $cheatSheetService->generateCheatSheet(
                $user,
                $this->title,
                $category,
                $topicDescription,
                $jobPosting
            );

            $this->closeModals();
            session()->flash('message', 'Cheat sheet generated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate cheat sheet: '.$e->getMessage());
        }
    }

    public function regenerateCheatSheet($cheatSheetId)
    {
        try {
            $cheatSheet = CheatSheet::where('user_id', Auth::id())->findOrFail($cheatSheetId);
            $cheatSheetService = app(CheatSheetService::class);
            $user = Auth::user();

            // Get the job posting if it exists
            $jobPosting = $cheatSheet->jobPosting;

            // Regenerate by creating a new one with the same parameters
            $newCheatSheet = $cheatSheetService->generateCheatSheet(
                $user,
                $cheatSheet->title,
                $cheatSheet->category,
                $cheatSheet->topic_description,
                $jobPosting
            );

            // Delete the old one
            $cheatSheet->delete();

            session()->flash('message', 'Cheat sheet regenerated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to regenerate cheat sheet: '.$e->getMessage());
        }
    }

    public function delete($cheatSheetId)
    {
        try {
            $cheatSheet = CheatSheet::where('user_id', Auth::id())->findOrFail($cheatSheetId);
            $cheatSheet->delete();

            session()->flash('message', 'Cheat sheet deleted successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete cheat sheet: '.$e->getMessage());
        }
    }

    public function getRecommendations()
    {
        try {
            $cheatSheetService = app(CheatSheetService::class);
            $user = Auth::user();

            $recommendations = $cheatSheetService->getRecommendedCheatSheets($user, 5);

            session()->flash('message', 'Study recommendations updated!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to get study recommendations: '.$e->getMessage());
        }
    }

    public function downloadCheatSheet($cheatSheetId)
    {
        try {
            $cheatSheet = CheatSheet::where('user_id', Auth::id())->findOrFail($cheatSheetId);
            session()->flash('message', 'Download will be available soon for: '.$cheatSheet->title);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to prepare download: '.$e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->title = '';
        $this->job_posting_id = '';
        $this->user_document_ids = [];
        $this->notes = '';
        $this->resetErrorBag();
    }

    private function loadOptions()
    {
        $this->jobPostings = JobPosting::where('user_id', Auth::id())
            ->orderBy('title')
            ->get();

        $this->userDocuments = UserDocument::where('user_id', Auth::id())
            ->where('type', 'resume') // Assuming we want resumes for cheat sheets
            ->orderBy('name')
            ->get();
    }

    private function getStats(): array
    {
        $baseQuery = CheatSheet::where('user_id', Auth::id());

        return [
            'total' => (clone $baseQuery)->count(),
            'practiced' => (clone $baseQuery)->where('usage_count', '>', 0)->count(),
            'average_score' => round((float) (clone $baseQuery)->whereNotNull('average_score')->avg('average_score'), 1),
            'recent_practice' => (clone $baseQuery)->whereNotNull('last_practiced_at')->where('last_practiced_at', '>=', now()->subDays(7))->count(),
        ];
    }
}
