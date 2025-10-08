<?php

namespace App\Livewire;

use App\Models\MasteryTopic;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MasteryTopicManager extends Component
{
    use WithPagination;

    public $showCreateModal = false;

    public $showEditModal = false;

    public $editingTopic = null;

    public $name = '';

    public $description = '';

    public $category = '';

    public $difficulty_level = 'beginner';

    public $is_active = true;

    public $search = '';

    public $categoryFilter = '';

    public $difficultyFilter = '';

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'required|string|max:100',
        'difficulty_level' => 'required|in:beginner,intermediate,advanced',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingDifficultyFilter()
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

    public function openEditModal($topicId)
    {
        $topic = MasteryTopic::findOrFail($topicId);
        $this->authorize('update', $topic);

        $this->editingTopic = $topic;
        $this->name = $topic->name;
        $this->description = $topic->description;
        $this->category = $topic->category;
        $this->difficulty_level = $topic->difficulty_level;
        $this->is_active = $topic->is_active;

        $this->showEditModal = true;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editingTopic = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->category = '';
        $this->difficulty_level = 'beginner';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingTopic) {
            $this->authorize('update', $this->editingTopic);
            $this->editingTopic->update([
                'name' => $this->name,
                'description' => $this->description,
                'category' => $this->category,
                'difficulty_level' => $this->difficulty_level,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Mastery topic updated successfully!');
        } else {
            $this->authorize('create', MasteryTopic::class);
            MasteryTopic::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'description' => $this->description,
                'category' => $this->category,
                'difficulty_level' => $this->difficulty_level,
                'is_active' => $this->is_active,
                'mastery_level' => 0,
            ]);

            session()->flash('message', 'Mastery topic created successfully!');
        }

        $this->closeModals();
    }

    public function delete($topicId)
    {
        $topic = MasteryTopic::findOrFail($topicId);
        $this->authorize('delete', $topic);

        $topic->delete();
        session()->flash('message', 'Mastery topic deleted successfully!');
    }

    public function toggleActive($topicId)
    {
        $topic = MasteryTopic::findOrFail($topicId);
        $this->authorize('update', $topic);

        $topic->update(['is_active' => ! $topic->is_active]);
        session()->flash('message', 'Mastery topic status updated!');
    }

    public function getTopicsProperty()
    {
        return MasteryTopic::where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%')
                        ->orWhere('category', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($this->difficultyFilter, function ($query) {
                $query->where('difficulty_level', $this->difficultyFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function getCategoriesProperty()
    {
        return MasteryTopic::where('user_id', Auth::id())
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();
    }

    public function getStatsProperty()
    {
        $userId = Auth::id();

        return [
            'total_topics' => MasteryTopic::where('user_id', $userId)->count(),
            'active_topics' => MasteryTopic::where('user_id', $userId)->where('is_active', true)->count(),
            'average_mastery' => MasteryTopic::where('user_id', $userId)->avg('mastery_level') ?? 0,
            'topics_practiced' => MasteryTopic::where('user_id', $userId)
                ->whereHas('interviewSessions')
                ->count(),
        ];
    }

    public function render()
    {
        return view('livewire.mastery-topic-manager', [
            'topics' => $this->topics,
            'categories' => $this->categories,
            'stats' => $this->stats,
        ]);
    }
}
