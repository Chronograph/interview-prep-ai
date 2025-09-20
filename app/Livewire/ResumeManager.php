<?php

namespace App\Livewire;

use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ResumeManager extends Component
{
    use WithFileUploads;

    public $resumes;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingResume = null;
    public $isUploading = false;
    public $uploadProgress = 0;

    // Form properties
    public $title = '';
    public $summary = '';
    public $skills = '';
    public $experience = '';
    public $education = '';
    public $certifications = '';
    public $file;

    protected $rules = [
        'title' => 'required|string|max:255',
        'summary' => 'nullable|string',
        'skills' => 'nullable|string',
        'experience' => 'nullable|string',
        'education' => 'nullable|string',
        'certifications' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240'
    ];

    public function mount($resumes = [])
    {
        $this->resumes = $resumes;
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

    public function openEditModal($resumeId)
    {
        $resume = Resume::find($resumeId);
        if ($resume && $resume->user_id === Auth::id()) {
            $this->editingResume = $resume;
            $this->title = $resume->title;
            $this->summary = $resume->summary;
            $this->skills = $resume->skills;
            $this->experience = $resume->experience;
            $this->education = $resume->education;
            $this->certifications = $resume->certifications;
            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingResume = null;
    }

    public function createResume()
    {
        $this->validate();

        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('resumes', 'public');
        }

        Resume::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'summary' => $this->summary,
            'skills' => $this->skills,
            'experience' => $this->experience,
            'education' => $this->education,
            'certifications' => $this->certifications,
            'file_path' => $filePath,
            'is_primary' => count($this->resumes) === 0
        ]);

        $this->refreshResumes();
        $this->closeCreateModal();
        $this->dispatch('resume-created');
        session()->flash('message', 'Resume created successfully!');
    }

    public function updateResume()
    {
        $this->validate();

        if (!$this->editingResume) return;

        $filePath = $this->editingResume->file_path;
        if ($this->file) {
            $filePath = $this->file->store('resumes', 'public');
        }

        $this->editingResume->update([
            'title' => $this->title,
            'summary' => $this->summary,
            'skills' => $this->skills,
            'experience' => $this->experience,
            'education' => $this->education,
            'certifications' => $this->certifications,
            'file_path' => $filePath
        ]);

        $this->refreshResumes();
        $this->closeEditModal();
        $this->dispatch('resume-updated');
        session()->flash('message', 'Resume updated successfully!');
    }

    public function deleteResume($resumeId)
    {
        $resume = Resume::find($resumeId);
        if ($resume && $resume->user_id === Auth::id()) {
            $resume->delete();
            $this->refreshResumes();
            $this->dispatch('resume-deleted');
            session()->flash('message', 'Resume deleted successfully!');
        }
    }

    public function setPrimary($resumeId)
    {
        // Reset all resumes to not primary
        Resume::where('user_id', Auth::id())->update(['is_primary' => false]);
        
        // Set selected resume as primary
        $resume = Resume::find($resumeId);
        if ($resume && $resume->user_id === Auth::id()) {
            $resume->update(['is_primary' => true]);
            $this->refreshResumes();
            $this->dispatch('primary-changed');
            session()->flash('message', 'Primary resume updated!');
        }
    }

    public function parseResume()
    {
        // Placeholder for AI parsing functionality
        // This would integrate with an AI service to parse the uploaded file
        $this->isUploading = true;
        $this->uploadProgress = 100;
        $this->isUploading = false;
    }

    private function resetForm()
    {
        $this->title = '';
        $this->summary = '';
        $this->skills = '';
        $this->experience = '';
        $this->education = '';
        $this->certifications = '';
        $this->file = null;
    }

    private function refreshResumes()
    {
        $this->resumes = Resume::where('user_id', Auth::id())->get()->toArray();
    }

    public function render()
    {
        return view('livewire.resume-manager');
    }
}
