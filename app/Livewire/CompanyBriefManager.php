<?php

namespace App\Livewire;

use App\Models\CompanyBrief;
use App\Models\JobPosting;
use App\Services\CompanyResearchService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyBriefManager extends Component
{
    use AuthorizesRequests, WithPagination;

    protected $researchService;

    // State properties
    public $showCreateModal = false;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $editingBrief = null;

    public $deleteBriefId = null;

    // Form fields
    public $company_name = '';

    public $job_posting_id = '';

    public $additional_context = '';

    // Edit form fields
    public $talking_points = [];

    public $potential_questions = [];

    public $reasons_to_work_here = [];

    public $notes = '';

    // List filtering
    public $search = '';

    public $industry = '';

    // Job postings for dropdowns
    public $jobPostings = [];

    public function mount(CompanyResearchService $researchService)
    {
        $this->researchService = $researchService;
        $this->loadJobPostings();
    }

    public function loadJobPostings()
    {
        $this->jobPostings = JobPosting::where('user_id', Auth::id())
            ->select('id', 'company as title')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedIndustry()
    {
        $this->resetPage();
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

    public function openEditModal($briefId)
    {
        $brief = CompanyBrief::findOrFail($briefId);
        $this->authorize('view', $brief);

        $this->editingBrief = $brief->id;
        $this->talking_points = $brief->talking_points ?? [];
        $this->potential_questions = $brief->potential_questions ?? [];
        $this->reasons_to_work_here = $brief->reasons_to_work_here ?? [];
        $this->notes = $brief->notes ?? '';

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingBrief = null;
    }

    public function openDeleteModal($briefId)
    {
        $this->deleteBriefId = $briefId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteBriefId = null;
    }

    public function createBrief()
    {
        $validated = $this->validate([
            'company_name' => 'required|string|max:255',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'additional_context' => 'nullable|string|max:1000',
        ]);

        try {
            $brief = $this->researchService->generateCompanyBrief(
                Auth::user(),
                $this->company_name
            );

            $this->closeCreateModal();
            session()->flash('success', 'Company brief generated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate company brief: '.$e->getMessage());
        }
    }

    public function updateBrief()
    {
        $brief = CompanyBrief::findOrFail($this->editingBrief);
        $this->authorize('update', $brief);

        $validated = $this->validate([
            'talking_points' => 'nullable|array',
            'talking_points.*' => 'string|max:500',
            'potential_questions' => 'nullable|array',
            'potential_questions.*' => 'string|max:500',
            'reasons_to_work_here' => 'nullable|array',
            'reasons_to_work_here.*' => 'string|max:500',
            'notes' => 'nullable|string|max:2000',
        ]);

        $brief->update($validated);

        $this->closeEditModal();
        session()->flash('success', 'Company brief updated successfully!');
    }

    public function deleteBrief()
    {
        $brief = CompanyBrief::findOrFail($this->deleteBriefId);
        $this->authorize('delete', $brief);

        $brief->delete();

        $this->closeDeleteModal();
        session()->flash('success', 'Company brief deleted successfully!');
    }

    public function refreshBrief($briefId)
    {
        $brief = CompanyBrief::findOrFail($briefId);
        $this->authorize('update', $brief);

        try {
            $updatedBrief = $this->researchService->generateCompanyBrief(
                Auth::user(),
                $brief->company_name
            );

            session()->flash('success', 'Company brief refreshed successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to refresh company brief: '.$e->getMessage());
        }
    }

    public function addTalkingPoint()
    {
        $this->talking_points[] = '';
    }

    public function removeTalkingPoint($index)
    {
        unset($this->talking_points[$index]);
        $this->talking_points = array_values($this->talking_points);
    }

    public function addPotentialQuestion()
    {
        $this->potential_questions[] = '';
    }

    public function removePotentialQuestion($index)
    {
        unset($this->potential_questions[$index]);
        $this->potential_questions = array_values($this->potential_questions);
    }

    public function addReasonToWorkHere()
    {
        $this->reasons_to_work_here[] = '';
    }

    public function removeReasonToWorkHere($index)
    {
        unset($this->reasons_to_work_here[$index]);
        $this->reasons_to_work_here = array_values($this->reasons_to_work_here);
    }

    private function resetForm()
    {
        $this->company_name = '';
        $this->job_posting_id = '';
        $this->additional_context = '';
        $this->talking_points = [];
        $this->potential_questions = [];
        $this->reasons_to_work_here = [];
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = Auth::user()->companyBriefs();

        if (! empty($this->search)) {
            $query->where('company_name', 'like', "%{$this->search}%")
                ->orWhere('industry', 'like', "%{$this->search}%");
        }

        if (! empty($this->industry)) {
            $query->where('industry', $this->industry);
        }

        $briefs = $query->orderBy('updated_at', 'desc')->paginate(12);

        return view('livewire.company-brief-manager', [
            'briefs' => $briefs,
        ]);
    }
}
