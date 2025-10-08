<?php

namespace App\Livewire;

use App\Models\AiPersona;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AiPersonaManager extends Component
{
    use AuthorizesRequests;

    // State properties
    public $personas = [];

    public $showCreateModal = false;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $editingPersona = null;

    public $deletePersonaId = null;

    // Form fields
    public $name = '';

    public $description = '';

    public $persona_type = 'technical';

    public $personality_traits = [];

    public $interview_style = 'friendly';

    public $difficulty_level = 'intermediate';

    public $system_prompt = '';

    public $sample_questions = [];

    public $is_active = true;

    // Form validation properties
    public $search = '';

    public $type = '';

    public $active_only = false;

    // Statistics and recommendations
    public $showStatsModal = false;

    public $showRecommendationsModal = false;

    public $showTestModal = false;

    public $showCloneModal = false;

    public $stats = [];

    public $recommendations = [];

    // Test persona fields
    public $test_question = '';

    public $test_result = '';

    // Clone fields
    public $cloneName = '';

    public $cloneDescription = '';

    public $originalPersonaId = null;

    // Options for dropdowns
    public $personaTypes = [
        'technical' => 'Technical',
        'behavioral' => 'Behavioral',
        'case_study' => 'Case Study',
        'general' => 'General',
        'industry_specific' => 'Industry Specific',
    ];

    public $interviewStyles = [
        'friendly' => 'Friendly',
        'challenging' => 'Challenging',
        'formal' => 'Formal',
        'casual' => 'Casual',
        'analytical' => 'Analytical',
    ];

    public $difficultyLevels = [
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
        'expert' => 'Expert',
    ];

    public function mount()
    {
        $this->loadPersonas();
    }

    public function loadPersonas()
    {
        $query = AiPersona::query();

        if (! empty($this->type)) {
            $query->where('persona_type', $this->type);
        }

        if (! empty($this->search)) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        }

        if ($this->active_only) {
            $query->where('is_active', true);
        }

        $this->personas = $query->orderBy('name')->paginate(20);
    }

    public function updatedSearch()
    {
        $this->loadPersonas();
    }

    public function updatedType()
    {
        $this->loadPersonas();
    }

    public function updatedActiveOnly()
    {
        $this->loadPersonas();
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

    public function openEditModal($personaId)
    {
        $persona = AiPersona::findOrFail($personaId);

        $this->editingPersona = $persona->id;
        $this->name = $persona->name;
        $this->description = $persona->description;
        $this->persona_type = $persona->persona_type;
        $this->personality_traits = $persona->personality_traits ?? [];
        $this->interview_style = $persona->interview_style;
        $this->difficulty_level = $persona->difficulty_level;
        $this->system_prompt = $persona->system_prompt;
        $this->sample_questions = $persona->sample_questions ?? [];
        $this->is_active = $persona->is_active;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingPersona = null;
    }

    public function openDeleteModal($personaId)
    {
        $this->deletePersonaId = $personaId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletePersonaId = null;
    }

    public function createPersona()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:ai_personas,name',
            'description' => 'required|string|max:1000',
            'persona_type' => 'required|in:technical,behavioral,case_study,general,industry_specific',
            'personality_traits' => 'required|array',
            'personality_traits.*' => 'string|max:100',
            'interview_style' => 'required|in:friendly,challenging,formal,casual,analytical',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced,expert',
            'system_prompt' => 'required|string|max:2000',
            'sample_questions' => 'nullable|array',
            'sample_questions.*' => 'string|max:500',
            'is_active' => 'boolean',
        ]);

        AiPersona::create([
            ...$validated,
            'usage_count' => 0,
        ]);

        $this->loadPersonas();
        $this->closeCreateModal();
        session()->flash('success', 'AI persona created successfully!');
    }

    public function updatePersona()
    {
        $persona = AiPersona::findOrFail($this->editingPersona);

        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:ai_personas,name,'.$persona->id,
            'description' => 'required|string|max:1000',
            'persona_type' => 'required|in:technical,behavioral,case_study,general,industry_specific',
            'personality_traits' => 'required|array',
            'personality_traits.*' => 'string|max:100',
            'interview_style' => 'required|in:friendly,challenging,formal,casual,analytical',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced,expert',
            'system_prompt' => 'required|string|max:2000',
            'sample_questions' => 'nullable|array',
            'sample_questions.*' => 'string|max:500',
            'is_active' => 'boolean',
        ]);

        $persona->update($validated);

        $this->loadPersonas();
        $this->closeEditModal();
        session()->flash('success', 'AI persona updated successfully!');
    }

    public function deletePersona()
    {
        $persona = AiPersona::findOrFail($this->deletePersonaId);

        // Check if persona is being used in any active sessions
        $activeSessionsCount = $persona->interviewSessions()
            ->where('status', 'in_progress')
            ->count();

        if ($activeSessionsCount > 0) {
            session()->flash('error', "Cannot delete persona that is being used in {$activeSessionsCount} active interview sessions");
            $this->closeDeleteModal();

            return;
        }

        $persona->delete();

        $this->loadPersonas();
        $this->closeDeleteModal();
        session()->flash('success', 'AI persona deleted successfully!');
    }

    public function toggleActive($personaId)
    {
        $persona = AiPersona::findOrFail($personaId);

        $persona->update([
            'is_active' => ! $persona->is_active,
        ]);

        $this->loadPersonas();
        session()->flash('success', 'AI persona status updated successfully!');
    }

    public function addPersonalityTrait()
    {
        $this->personality_traits[] = '';
    }

    public function removePersonalityTrait($index)
    {
        unset($this->personality_traits[$index]);
        $this->personality_traits = array_values($this->personality_traits);
    }

    public function addSampleQuestion()
    {
        $this->sample_questions[] = '';
    }

    public function removeSampleQuestion($index)
    {
        unset($this->sample_questions[$index]);
        $this->sample_questions = array_values($this->sample_questions);
    }

    // Statistical and advanced features
    public function openStatsModal()
    {
        $this->stats = [
            'total_personas' => AiPersona::count(),
            'active_personas' => AiPersona::where('is_active', true)->count(),
            'by_type' => AiPersona::selectRaw('persona_type, COUNT(*) as count')
                ->groupBy('persona_type')
                ->pluck('count', 'persona_type'),
            'by_difficulty' => AiPersona::selectRaw('difficulty_level, COUNT(*) as count')
                ->groupBy('difficulty_level')
                ->pluck('count', 'difficulty_level'),
            'most_used' => AiPersona::orderBy('usage_count', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'usage_count', 'persona_type']),
        ];

        $this->showStatsModal = true;
    }

    public function closeStatsModal()
    {
        $this->showStatsModal = false;
        $this->stats = [];
    }

    public function openRecommendationsModal()
    {
        $this->showRecommendationsModal = true;
    }

    public function closeRecommendationsModal()
    {
        $this->showRecommendationsModal = false;
        $this->recommendations = [];
    }

    public function generateRecommendations()
    {
        $query = AiPersona::where('is_active', true);

        $query->orderBy('usage_count', 'desc')->limit(6);
        $this->recommendations = $query->get();

        session()->flash('message', 'Personas recommended based on your criteria');
    }

    public function openTestModal($personaId)
    {
        $persona = AiPersona::findOrFail($personaId);
        $this->originalPersonaId = $personaId;

        // Pick a random sample question or use default
        if (! empty($persona->sample_questions)) {
            $this->test_question = $persona->sample_questions[array_rand($persona->sample_questions)];
        } else {
            $this->test_question = 'Tell me about yourself and your background.';
        }

        $this->test_result = 'This is how this persona would conduct an interview';
        $this->showTestModal = true;
    }

    public function closeTestModal()
    {
        $this->showTestModal = false;
        $this->test_question = '';
        $this->test_result = '';
        $this->originalPersonaId = null;
    }

    public function openCloneModal($personaId)
    {
        $persona = AiPersona::findOrFail($personaId);
        $this->originalPersonaId = $personaId;
        $this->cloneName = $persona->name.' (Copy)';
        $this->cloneDescription = $persona->description.' (Copy)';
        $this->showCloneModal = true;
    }

    public function closeCloneModal()
    {
        $this->showCloneModal = false;
        $this->cloneName = '';
        $this->cloneDescription = '';
        $this->originalPersonaId = null;
    }

    public function duplicatePersona()
    {
        $validated = $this->validate([
            'cloneName' => 'required|string|max:255|unique:ai_personas,name',
            'cloneDescription' => 'nullable|string|max:1000',
        ]);

        $originalPersona = AiPersona::findOrFail($this->originalPersonaId);

        $clonedPersona = $originalPersona->replicate();
        $clonedPersona->name = $this->cloneName;
        $clonedPersona->description = $this->cloneDescription ?? $originalPersona->description.' (Copy)';
        $clonedPersona->usage_count = 0;
        $clonedPersona->save();

        $this->loadPersonas();
        $this->closeCloneModal();
        session()->flash('success', 'AI persona cloned successfully!');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->persona_type = 'technical';
        $this->personality_traits = [];
        $this->interview_style = 'friendly';
        $this->difficulty_level = 'intermediate';
        $this->system_prompt = '';
        $this->sample_questions = [];
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.ai-persona-manager');
    }
}
