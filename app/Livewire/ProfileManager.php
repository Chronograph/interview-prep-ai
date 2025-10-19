<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileManager extends Component
{
    use WithFileUploads;

    // User properties
    public $user;

    public $name;

    public $email;

    public $phone;

    public $location;

    public $timezone;

    public $bio;

    public $current_title;

    public $current_company;

    public $years_experience;

    public $linkedin_url;

    public $github_url;

    public $portfolio_url;

    public $target_roles = [];

    public $target_companies = [];

    public $target_salary_min;

    public $target_salary_max;

    public $skills = [];

    public $certifications = [];

    public $preferred_interview_types = [];

    // Professional Summary
    public $professional_summary;

    public $headline;

    public $objective;

    // Work Experience
    public $work_experience = [];

    // Education
    public $education = [];

    // Projects
    public $projects = [];

    // Languages
    public $languages = [];

    // Awards
    public $awards = [];

    // Publications
    public $publications = [];

    // Volunteer Work
    public $volunteer_work = [];

    // Interests
    public $interests = [];

    // Target Industries
    public $target_industries = [];

    // Profile completion
    public $profile_completion_percentage = 0;

    // Password update
    public $current_password;

    public $password;

    public $password_confirmation;

    public $showPasswordSection = false;

    // Profile photo
    public $profile_photo;

    public $profile_photo_path;

    // File upload
    public $tempProfilePhoto;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore(Auth::id())],
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'current_title' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'years_experience' => 'nullable|integer|min:0|max:50',
            'linkedin_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'portfolio_url' => 'nullable|url|max:500',
            'target_roles' => 'nullable|array',
            'target_companies' => 'nullable|array',
            'target_salary_min' => 'nullable|integer|min:0',
            'target_salary_max' => 'nullable|integer|min:0|gte:target_salary_min',
            'skills' => 'nullable|array',
            'certifications' => 'nullable|array',
            'preferred_interview_types' => 'nullable|array',
            'professional_summary' => 'nullable|string|max:2000',
            'headline' => 'nullable|string|max:255',
            'objective' => 'nullable|string|max:1000',
            'work_experience' => 'nullable|array',
            'education' => 'nullable|array',
            'projects' => 'nullable|array',
            'languages' => 'nullable|array',
            'awards' => 'nullable|array',
            'publications' => 'nullable|array',
            'volunteer_work' => 'nullable|array',
            'interests' => 'nullable|array',
            'target_industries' => 'nullable|array',
            'current_password' => 'required_with:password|current_password|nullable',
            'password' => 'nullable|confirmed|min:8',
            'password_confirmation' => 'nullable|same:password',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected $messages = [
        'target_salary_max.gte' => 'The maximum target salary must be greater than or equal to the minimum target salary.',
        'password.confirmed' => 'The password confirmation does not match.',
        'password_confirmation.same' => 'The password confirmation does not match.',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->fillProfileData();
    }

    private function fillProfileData()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->location = $this->user->location;
        $this->bio = $this->user->bio;
        $this->current_title = $this->user->current_title;
        $this->current_company = $this->user->current_company;
        $this->years_experience = $this->user->years_experience;
        $this->linkedin_url = $this->user->linkedin_url;
        $this->github_url = $this->user->github_url;
        $this->portfolio_url = $this->user->portfolio_url;
        $this->target_roles = $this->user->target_roles ?? [];
        $this->target_companies = $this->user->target_companies ?? [];
        $this->target_salary_min = $this->user->target_salary_min;
        $this->target_salary_max = $this->user->target_salary_max;
        $this->skills = $this->user->skills ?? [];
        $this->certifications = $this->user->certifications ?? [];
        $this->preferred_interview_types = $this->user->preferred_interview_types ?? [];
        $this->professional_summary = $this->user->professional_summary;
        $this->headline = $this->user->headline;
        $this->objective = $this->user->objective;
        $this->work_experience = $this->user->work_experience ?? [];
        $this->education = $this->user->education ?? [];
        $this->projects = $this->user->projects ?? [];
        $this->languages = $this->user->languages ?? [];
        $this->awards = $this->user->awards ?? [];
        $this->publications = $this->user->publications ?? [];
        $this->volunteer_work = $this->user->volunteer_work ?? [];
        $this->interests = $this->user->interests ?? [];
        $this->target_industries = $this->user->target_industries ?? [];
        $this->profile_photo_path = $this->user->profile_photo_path;
        $this->profile_completion_percentage = $this->user->profile_completion_percentage ?? 0;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function saveProfile()
    {
        $this->validate($this->rules());

        // Handle profile photo upload
        if ($this->profile_photo) {
            $this->user->profile_photo_path = $this->profile_photo->store('profile-photos', 'public');
        }

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'bio' => $this->bio,
            'current_title' => $this->current_title,
            'current_company' => $this->current_company,
            'years_experience' => $this->years_experience,
            'linkedin_url' => $this->linkedin_url,
            'github_url' => $this->github_url,
            'portfolio_url' => $this->portfolio_url,
            'target_roles' => $this->target_roles,
            'target_companies' => $this->target_companies,
            'target_salary_min' => $this->target_salary_min,
            'target_salary_max' => $this->target_salary_max,
            'skills' => $this->skills,
            'certifications' => $this->certifications,
            'preferred_interview_types' => $this->preferred_interview_types,
            'professional_summary' => $this->professional_summary,
            'headline' => $this->headline,
            'objective' => $this->objective,
            'work_experience' => $this->work_experience,
            'education' => $this->education,
            'projects' => $this->projects,
            'languages' => $this->languages,
            'awards' => $this->awards,
            'publications' => $this->publications,
            'volunteer_work' => $this->volunteer_work,
            'interests' => $this->interests,
            'target_industries' => $this->target_industries,
        ];

        if ($this->user->profile_photo_path) {
            $updateData['profile_photo_path'] = $this->user->profile_photo_path;
        }

        $this->user->update($updateData);

        // Recalculate profile completion
        $this->user->updateProfileCompletion();

        session()->flash('message', 'Profile updated successfully!');
        $this->fillProfileData();
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', Password::defaults(), 'confirmed'],
            'password_confirmation' => 'required|same:password',
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        session()->flash('password_updated', 'Password updated successfully!');

        $this->resetPasswordFields();
    }

    public function togglePasswordSection()
    {
        $this->showPasswordSection = ! $this->showPasswordSection;
        $this->resetPasswordFields();
    }

    private function resetPasswordFields()
    {
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function addSkill()
    {
        $this->skills[] = '';
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function addTargetRole()
    {
        $this->target_roles[] = '';
    }

    public function removeTargetRole($index)
    {
        unset($this->target_roles[$index]);
        $this->target_roles = array_values($this->target_roles);
    }

    // Work Experience Management
    public function addWorkExperience()
    {
        $this->work_experience[] = [
            'title' => '',
            'company' => '',
            'duration' => '',
            'description' => '',
            'achievements' => []
        ];
    }

    public function removeWorkExperience($index)
    {
        unset($this->work_experience[$index]);
        $this->work_experience = array_values($this->work_experience);
    }

    public function addAchievement($experienceIndex)
    {
        $this->work_experience[$experienceIndex]['achievements'][] = '';
    }

    public function removeAchievement($experienceIndex, $achievementIndex)
    {
        unset($this->work_experience[$experienceIndex]['achievements'][$achievementIndex]);
        $this->work_experience[$experienceIndex]['achievements'] = array_values($this->work_experience[$experienceIndex]['achievements']);
    }

    // Education Management
    public function addEducation()
    {
        $this->education[] = [
            'degree' => '',
            'institution' => '',
            'year' => '',
            'gpa' => ''
        ];
    }

    public function removeEducation($index)
    {
        unset($this->education[$index]);
        $this->education = array_values($this->education);
    }

    // Project Management
    public function addProject()
    {
        $this->projects[] = [
            'name' => '',
            'description' => '',
            'technologies' => [],
            'url' => ''
        ];
    }

    public function removeProject($index)
    {
        unset($this->projects[$index]);
        $this->projects = array_values($this->projects);
    }

    public function addProjectTechnology($projectIndex)
    {
        $this->projects[$projectIndex]['technologies'][] = '';
    }

    public function removeProjectTechnology($projectIndex, $techIndex)
    {
        unset($this->projects[$projectIndex]['technologies'][$techIndex]);
        $this->projects[$projectIndex]['technologies'] = array_values($this->projects[$projectIndex]['technologies']);
    }

    // Certification Management
    public function addCertification()
    {
        $this->certifications[] = [
            'name' => '',
            'issuer' => '',
            'year' => ''
        ];
    }

    public function removeCertification($index)
    {
        unset($this->certifications[$index]);
        $this->certifications = array_values($this->certifications);
    }

    // Volunteer Work Management
    public function addVolunteerWork()
    {
        $this->volunteer_work[] = [
            'role' => '',
            'organization' => '',
            'duration' => '',
            'description' => ''
        ];
    }

    public function removeVolunteerWork($index)
    {
        unset($this->volunteer_work[$index]);
        $this->volunteer_work = array_values($this->volunteer_work);
    }

    // Array Management Helpers
    public function addArrayItem($arrayName)
    {
        $this->{$arrayName}[] = '';
    }

    public function removeArrayItem($arrayName, $index)
    {
        unset($this->{$arrayName}[$index]);
        $this->{$arrayName} = array_values($this->{$arrayName});
    }

    // Auto-populate from resume
    public function autoPopulateFromResume()
    {
        $latestResume = $this->user->resumes()->latest()->first();
        
        if (!$latestResume) {
            session()->flash('error', 'No resume found to auto-populate from.');
            return;
        }

        // Populate basic information
        if ($latestResume->headline && !$this->headline) {
            $this->headline = $latestResume->headline;
        }
        
        if ($latestResume->summary && !$this->professional_summary) {
            $this->professional_summary = $latestResume->summary;
        }
        
        if ($latestResume->objective && !$this->objective) {
            $this->objective = $latestResume->objective;
        }

        // Populate work experience
        if ($latestResume->experience && empty($this->work_experience)) {
            $this->work_experience = $latestResume->experience;
        }

        // Populate education
        if ($latestResume->education && empty($this->education)) {
            $this->education = $latestResume->education;
        }

        // Populate skills
        if ($latestResume->skills && empty($this->skills)) {
            $this->skills = $latestResume->skills;
        }

        // Populate projects
        if ($latestResume->projects && empty($this->projects)) {
            $this->projects = $latestResume->projects;
        }

        // Populate other fields
        if ($latestResume->certifications && empty($this->certifications)) {
            $this->certifications = $latestResume->certifications;
        }

        if ($latestResume->languages && empty($this->languages)) {
            $this->languages = $latestResume->languages;
        }

        if ($latestResume->awards && empty($this->awards)) {
            $this->awards = $latestResume->awards;
        }

        if ($latestResume->publications && empty($this->publications)) {
            $this->publications = $latestResume->publications;
        }

        if ($latestResume->volunteer_work && empty($this->volunteer_work)) {
            $this->volunteer_work = $latestResume->volunteer_work;
        }

        if ($latestResume->interests && empty($this->interests)) {
            $this->interests = $latestResume->interests;
        }

        session()->flash('success', 'Profile auto-populated from latest resume!');
    }

    public function render()
    {
        return view('livewire.profile-manager');
    }
}
