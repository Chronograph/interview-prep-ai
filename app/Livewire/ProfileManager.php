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
        $this->profile_photo_path = $this->user->profile_photo_path;
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

    public function render()
    {
        return view('livewire.profile-manager');
    }
}
