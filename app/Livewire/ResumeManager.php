<?php

namespace App\Livewire;

use App\Livewire\Forms\ResumeForm;
use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.app')]
#[Title('Resume Manager')]
class ResumeManager extends Component
{
    use WireUiActions, WithFileUploads;

    public ResumeForm $form;

    public $resumes;

    // Statistics
    public $totalResumes = 0;

    public $avgOptimization = 76;

    public $companiesTargeted = 6;

    public $isUploading = false;

    public $uploadProgress = 0;

    public $selectedResumeVersions = [];

    public function mount()
    {
        $this->refreshResumes();
        $this->calculateStatistics();
    }

    public function switchResumeVersion($groupId, $resumeId)
    {
        $this->selectedResumeVersions[$groupId] = $resumeId;
    }

    public function getGroupedResumes()
    {
        $allResumes = Resume::where('user_id', Auth::id())->get();

        // Get base resumes (those without a parent)
        $baseResumes = $allResumes->whereNull('parent_resume_id');

        $grouped = [];
        foreach ($baseResumes as $base) {
            $groupId = $base->id;

            // Get all versions for this base resume
            $versions = $allResumes->where('parent_resume_id', $base->id)
                ->push($base)
                ->sortByDesc('version')
                ->values();

            // Set the selected version if not already set
            if (! isset($this->selectedResumeVersions[$groupId])) {
                $this->selectedResumeVersions[$groupId] = $versions->first()->id;
            }

            $grouped[] = [
                'group_id' => $groupId,
                'versions' => $versions,
                'selected_version_id' => $this->selectedResumeVersions[$groupId],
                'selected_resume' => $versions->firstWhere('id', $this->selectedResumeVersions[$groupId]),
            ];
        }

        return collect($grouped);
    }

    public function openCreateModal()
    {
        $this->form->reset();
        $this->js('$openModal("createResumeModal")');
    }

    public function openEditModal($resumeId)
    {
        $resume = Resume::find($resumeId);
        if ($resume && $resume->user_id === Auth::id()) {
            $this->form->setResume($resume);
            $this->js('$openModal("editResumeModal")');
        }
    }

    public function createResume()
    {
        $filePath = null;
        $fileSize = null;

        if ($this->form->file) {
            $filePath = $this->form->file->store('resumes', 'public');
            // Get file size in KB
            $fileSize = round($this->form->file->getSize() / 1024);
        }

        $this->form->store($filePath, $fileSize);

        $this->refreshResumes();
        $this->calculateStatistics();
        $this->js('$closeModal("createResumeModal")');
        $this->dispatch('resume-created');

        $this->notification()->success(
            'Resume Created!',
            'Your resume has been created successfully.'
        );
    }

    public function updateResume()
    {
        $filePath = null;
        $fileSize = null;

        if ($this->form->file) {
            $filePath = $this->form->file->store('resumes', 'public');
            // Get file size in KB
            $fileSize = round($this->form->file->getSize() / 1024);
        }

        $this->form->update($filePath, $fileSize);

        $this->refreshResumes();
        $this->calculateStatistics();
        $this->js('$closeModal("editResumeModal")');
        $this->dispatch('resume-updated');

        $this->notification()->success(
            'Resume Updated!',
            'Your resume has been updated successfully.'
        );
    }

    public function createVersion($resumeId)
    {
        $resume = Resume::find($resumeId);
        if ($resume && $resume->user_id === Auth::id()) {
            $newVersion = $resume->createVersion();
            $newVersion->updateOptimizationScore();
            $this->refreshResumes();
            $this->calculateStatistics();
            $this->dispatch('version-created');

            $this->notification()->success(
                'Version Created!',
                'New resume version (v'.$newVersion->version.') created successfully.'
            );
        }
    }

    public function deleteResume($resumeId)
    {
        $resume = Resume::find($resumeId);
        if ($resume && $resume->user_id === Auth::id()) {
            $resumeTitle = $resume->title;
            $resume->delete();
            $this->refreshResumes();
            $this->calculateStatistics();
            $this->dispatch('resume-deleted');

            $this->notification()->success(
                'Resume Deleted!',
                '"'.$resumeTitle.'" has been deleted successfully.'
            );
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

            $this->notification()->success(
                'Primary Resume Updated!',
                '"'.$resume->title.'" is now your primary resume.'
            );
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

    private function refreshResumes()
    {
        $this->resumes = Resume::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function calculateStatistics()
    {
        $this->totalResumes = $this->resumes->count();

        // Calculate average optimization based on resume completeness
        $totalOptimization = 0;
        foreach ($this->resumes as $resume) {
            $score = 0;
            if ($resume->title) {
                $score += 20;
            }
            if ($resume->summary) {
                $score += 20;
            }
            if ($resume->skills) {
                $score += 20;
            }
            if ($resume->experience) {
                $score += 20;
            }
            if ($resume->education) {
                $score += 10;
            }
            if ($resume->certifications) {
                $score += 10;
            }
            $totalOptimization += $score;
        }

        $this->avgOptimization = $this->totalResumes > 0 ? round($totalOptimization / $this->totalResumes) : 0;

        // Calculate unique companies targeted (placeholder - would need job applications data)
        $this->companiesTargeted = min(6, $this->totalResumes * 2); // Placeholder calculation
    }

    public function render()
    {
        return view('livewire.resume-manager');
    }
}
