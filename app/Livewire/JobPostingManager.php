<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class JobPostingManager extends Component
{
    use WithFileUploads;

    // Component state
    public $jobPostings = [];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingJobPosting = null;
    public $isUploading = false;
    public $uploadProgress = 0;

    // Form fields
    public $title = '';
    public $company = '';
    public $description = '';
    public $requirements = '';
    public $location = '';
    public $salary_range = '';
    public $employment_type = 'full-time';
    public $file;

    // Employment types
    public $employmentTypes = [
        ['value' => 'full-time', 'label' => 'Full Time'],
        ['value' => 'part-time', 'label' => 'Part Time'],
        ['value' => 'contract', 'label' => 'Contract'],
        ['value' => 'internship', 'label' => 'Internship'],
        ['value' => 'freelance', 'label' => 'Freelance']
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'company' => 'required|string|max:255',
        'description' => 'required|string',
        'requirements' => 'nullable|string',
        'location' => 'nullable|string|max:255',
        'salary_range' => 'nullable|string|max:255',
        'employment_type' => 'required|in:full-time,part-time,contract,internship,freelance',
        'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240'
    ];

    public function mount()
    {
        $this->loadJobPostings();
    }

    public function loadJobPostings()
    {
        $this->jobPostings = JobPosting::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
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

    public function openEditModal($jobPostingId)
    {
        $jobPosting = JobPosting::findOrFail($jobPostingId);
        $this->editingJobPosting = $jobPosting->id;
        $this->title = $jobPosting->title;
        $this->company = $jobPosting->company;
        $this->description = $jobPosting->description;
        $this->requirements = $jobPosting->requirements ?? '';
        $this->location = $jobPosting->location ?? '';
        $this->salary_range = $jobPosting->salary_range ?? '';
        $this->employment_type = $jobPosting->employment_type;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingJobPosting = null;
    }

    public function createJobPosting()
    {
        $this->validate();

        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('job-postings', 'public');
        }

        JobPosting::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'company' => $this->company,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'location' => $this->location,
            'salary_range' => $this->salary_range,
            'employment_type' => $this->employment_type,
            'file_path' => $filePath
        ]);

        $this->loadJobPostings();
        $this->closeCreateModal();
        session()->flash('message', 'Job posting created successfully!');
    }

    public function updateJobPosting()
    {
        $this->validate();

        $jobPosting = JobPosting::findOrFail($this->editingJobPosting);
        
        $jobPosting->update([
            'title' => $this->title,
            'company' => $this->company,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'location' => $this->location,
            'salary_range' => $this->salary_range,
            'employment_type' => $this->employment_type
        ]);

        $this->loadJobPostings();
        $this->closeEditModal();
        session()->flash('message', 'Job posting updated successfully!');
    }

    public function deleteJobPosting($jobPostingId)
    {
        $jobPosting = JobPosting::findOrFail($jobPostingId);
        
        // Delete associated file if exists
        if ($jobPosting->file_path) {
            Storage::disk('public')->delete($jobPosting->file_path);
        }
        
        $jobPosting->delete();
        $this->loadJobPostings();
        session()->flash('message', 'Job posting deleted successfully!');
    }

    public function parseJobPosting()
    {
        if (!$this->file) return;

        $this->isUploading = true;
        $this->uploadProgress = 0;

        try {
            // Simulate parsing progress
            for ($i = 0; $i <= 100; $i += 20) {
                $this->uploadProgress = $i;
                usleep(100000); // 0.1 second delay
            }

            // Here you would implement actual file parsing logic
            // For now, we'll just set some default values
            if (empty($this->title)) {
                $this->title = 'Parsed Job Title';
            }
            if (empty($this->company)) {
                $this->company = 'Parsed Company Name';
            }
            if (empty($this->description)) {
                $this->description = 'Parsed job description from uploaded file.';
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to parse job posting. Please fill in the details manually.');
        } finally {
            $this->isUploading = false;
            $this->uploadProgress = 0;
        }
    }

    public function updatedFile()
    {
        if ($this->file) {
            $this->parseJobPosting();
        }
    }

    private function resetForm()
    {
        $this->title = '';
        $this->company = '';
        $this->description = '';
        $this->requirements = '';
        $this->location = '';
        $this->salary_range = '';
        $this->employment_type = 'full-time';
        $this->file = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.job-posting-manager');
    }
}
