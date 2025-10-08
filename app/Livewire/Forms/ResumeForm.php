<?php

namespace App\Livewire\Forms;

use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResumeForm extends Form
{
    public ?Resume $resume = null;

    // Basic Info
    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('nullable|integer')]
    public $version = 1;

    // Personal Information
    #[Validate('nullable|string|max:255')]
    public $full_name = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:50')]
    public $phone = '';

    #[Validate('nullable|string|max:255')]
    public $location = '';

    #[Validate('nullable|url|max:255')]
    public $linkedin_url = '';

    #[Validate('nullable|url|max:255')]
    public $portfolio_url = '';

    #[Validate('nullable|url|max:255')]
    public $github_url = '';

    // Professional Details
    #[Validate('nullable|string|max:255')]
    public $headline = '';

    #[Validate('nullable|string')]
    public $summary = '';

    #[Validate('nullable|string')]
    public $objective = '';

    #[Validate('nullable|string')]
    public $skills = '';

    #[Validate('nullable|string')]
    public $experience = '';

    #[Validate('nullable|string')]
    public $education = '';

    #[Validate('nullable|string')]
    public $certifications = '';

    #[Validate('nullable|string')]
    public $projects = '';

    #[Validate('nullable|string')]
    public $languages = '';

    #[Validate('nullable|string')]
    public $awards = '';

    #[Validate('nullable|string')]
    public $publications = '';

    #[Validate('nullable|string')]
    public $volunteer_work = '';

    #[Validate('nullable|string')]
    public $references = '';

    #[Validate('nullable|string')]
    public $interests = '';

    // Metadata
    #[Validate('nullable|array')]
    public $optimized_companies = [];

    #[Validate('nullable|array')]
    public $optimized_roles = [];

    #[Validate('nullable|file|mimes:pdf,doc,docx,txt|max:10240')]
    public $file;

    public function setResume(Resume $resume)
    {
        $this->resume = $resume;
        $this->title = $resume->title;
        $this->version = $resume->version;

        // Personal Information
        $this->full_name = $resume->full_name;
        $this->email = $resume->email;
        $this->phone = $resume->phone;
        $this->location = $resume->location;
        $this->linkedin_url = $resume->linkedin_url;
        $this->portfolio_url = $resume->portfolio_url;
        $this->github_url = $resume->github_url;

        // Professional Details
        $this->headline = $resume->headline;
        $this->summary = $resume->summary;
        $this->objective = $resume->objective;
        $this->skills = is_array($resume->skills) ? implode(', ', $resume->skills) : $resume->skills;
        $this->experience = is_array($resume->experience) ? implode("\n", $resume->experience) : $resume->experience;
        $this->education = is_array($resume->education) ? implode("\n", $resume->education) : $resume->education;
        $this->certifications = is_array($resume->certifications) ? implode("\n", $resume->certifications) : $resume->certifications;
        $this->projects = is_array($resume->projects) ? implode("\n", $resume->projects) : $resume->projects;
        $this->languages = is_array($resume->languages) ? implode(', ', $resume->languages) : $resume->languages;
        $this->awards = is_array($resume->awards) ? implode("\n", $resume->awards) : $resume->awards;
        $this->publications = is_array($resume->publications) ? implode("\n", $resume->publications) : $resume->publications;
        $this->volunteer_work = is_array($resume->volunteer_work) ? implode("\n", $resume->volunteer_work) : $resume->volunteer_work;
        $this->references = is_array($resume->references) ? implode("\n", $resume->references) : $resume->references;
        $this->interests = is_array($resume->interests) ? implode(', ', $resume->interests) : $resume->interests;

        // Metadata
        $this->optimized_companies = $resume->optimized_companies ?? [];
        $this->optimized_roles = $resume->optimized_roles ?? [];
    }

    public function store($filePath = null, $fileSize = null)
    {
        $this->validate();

        $resume = Resume::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'version' => $this->version,
            // Personal Information
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'linkedin_url' => $this->linkedin_url,
            'portfolio_url' => $this->portfolio_url,
            'github_url' => $this->github_url,
            // Professional Details
            'headline' => $this->headline,
            'summary' => $this->summary,
            'objective' => $this->objective,
            'skills' => $this->skills ? array_map('trim', explode(',', $this->skills)) : [],
            'experience' => $this->experience ? array_filter(array_map('trim', explode("\n", $this->experience))) : [],
            'education' => $this->education ? array_filter(array_map('trim', explode("\n", $this->education))) : [],
            'certifications' => $this->certifications ? array_filter(array_map('trim', explode("\n", $this->certifications))) : [],
            'projects' => $this->projects ? array_filter(array_map('trim', explode("\n", $this->projects))) : [],
            'languages' => $this->languages ? array_map('trim', explode(',', $this->languages)) : [],
            'awards' => $this->awards ? array_filter(array_map('trim', explode("\n", $this->awards))) : [],
            'publications' => $this->publications ? array_filter(array_map('trim', explode("\n", $this->publications))) : [],
            'volunteer_work' => $this->volunteer_work ? array_filter(array_map('trim', explode("\n", $this->volunteer_work))) : [],
            'references' => $this->references ? array_filter(array_map('trim', explode("\n", $this->references))) : [],
            'interests' => $this->interests ? array_map('trim', explode(',', $this->interests)) : [],
            // Metadata
            'optimized_companies' => $this->optimized_companies,
            'optimized_roles' => $this->optimized_roles,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'is_primary' => count(Resume::where('user_id', Auth::id())->get()) === 0,
        ]);

        // Calculate and update optimization score
        $resume->updateOptimizationScore();

        $this->reset();

        return $resume;
    }

    public function update($filePath = null, $fileSize = null)
    {
        $this->validate();

        if (! $this->resume) {
            return null;
        }

        $this->resume->update([
            'title' => $this->title,
            'version' => $this->version,
            // Personal Information
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'linkedin_url' => $this->linkedin_url,
            'portfolio_url' => $this->portfolio_url,
            'github_url' => $this->github_url,
            // Professional Details
            'headline' => $this->headline,
            'summary' => $this->summary,
            'objective' => $this->objective,
            'skills' => $this->skills ? array_map('trim', explode(',', $this->skills)) : [],
            'experience' => $this->experience ? array_filter(array_map('trim', explode("\n", $this->experience))) : [],
            'education' => $this->education ? array_filter(array_map('trim', explode("\n", $this->education))) : [],
            'certifications' => $this->certifications ? array_filter(array_map('trim', explode("\n", $this->certifications))) : [],
            'projects' => $this->projects ? array_filter(array_map('trim', explode("\n", $this->projects))) : [],
            'languages' => $this->languages ? array_map('trim', explode(',', $this->languages)) : [],
            'awards' => $this->awards ? array_filter(array_map('trim', explode("\n", $this->awards))) : [],
            'publications' => $this->publications ? array_filter(array_map('trim', explode("\n", $this->publications))) : [],
            'volunteer_work' => $this->volunteer_work ? array_filter(array_map('trim', explode("\n", $this->volunteer_work))) : [],
            'references' => $this->references ? array_filter(array_map('trim', explode("\n", $this->references))) : [],
            'interests' => $this->interests ? array_map('trim', explode(',', $this->interests)) : [],
            // Metadata
            'optimized_companies' => $this->optimized_companies,
            'optimized_roles' => $this->optimized_roles,
            'file_path' => $filePath ?? $this->resume->file_path,
            'file_size' => $fileSize ?? $this->resume->file_size,
        ]);

        // Calculate and update optimization score
        $this->resume->updateOptimizationScore();

        return $this->resume;
    }
}
