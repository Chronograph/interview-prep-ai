<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'location',
        'timezone',
        'profile_photo_path',
        'bio',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'current_title',
        'current_company',
        'years_experience',
        'target_roles',
        'target_companies',
        'target_salary_min',
        'target_salary_max',
        'skills',
        'certifications',
        'education',
        'preferred_interview_types',
        'availability_schedule',
        'notification_preferences',
        'privacy_settings',
        'onboarding_completed',
        'profile_completion_percentage',
        'last_active_at',
        // New comprehensive profile fields
        'headline',
        'professional_summary',
        'objective',
        'work_experience',
        'projects',
        'languages',
        'awards',
        'publications',
        'volunteer_work',
        'interests',
        'target_industries',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'target_roles' => 'array',
            'target_companies' => 'array',
            'target_salary_min' => 'decimal:2',
            'target_salary_max' => 'decimal:2',
            'skills' => 'array',
            'certifications' => 'array',
            'education' => 'array',
            'work_experience' => 'array',
            'projects' => 'array',
            'languages' => 'array',
            'awards' => 'array',
            'publications' => 'array',
            'volunteer_work' => 'array',
            'interests' => 'array',
            'target_industries' => 'array',
            'preferred_interview_types' => 'array',
            'availability_schedule' => 'array',
            'notification_preferences' => 'array',
            'privacy_settings' => 'array',
            'onboarding_completed' => 'boolean',
            'last_active_at' => 'datetime',
        ];
    }

    // Relationships
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class);
    }

    public function companyBriefs(): HasMany
    {
        return $this->hasMany(CompanyBrief::class);
    }

    public function cheatSheets(): HasMany
    {
        return $this->hasMany(CheatSheet::class);
    }

    public function masteryTopics(): HasMany
    {
        return $this->hasMany(MasteryTopic::class);
    }

    public function masteryScores(): HasMany
    {
        return $this->hasMany(MasteryScore::class);
    }

    public function topicProgress(): HasMany
    {
        return $this->hasMany(TopicProgress::class);
    }

    public function userDocuments(): HasMany
    {
        return $this->hasMany(UserDocument::class);
    }

    public function interviewSessions(): HasMany
    {
        return $this->hasMany(InterviewSession::class);
    }

    public function ownedOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'owner_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get the user's current organization (first one they're a member of)
     */
    public function currentOrganization(): ?Organization
    {
        return $this->organizations()->first();
    }

    /**
     * Get the user's primary organization (owned or first membership)
     */
    public function primaryOrganization(): ?Organization
    {
        try {
            return $this->ownedOrganizations()->first() ?? $this->organizations()->first();
        } catch (\Exception $e) {
            logger()->error('Failed to get primary organization: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get the user's settings
     */
    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * Get or create user settings with defaults
     */
    public function getSettings()
    {
        return $this->settings()->firstOrCreate([], [
            'job_applications_per_week' => 5,
            'practice_interviews_per_week' => 3,
            'score_improvement_target' => 10,
            'email_goal_reminders' => true,
            'email_weekly_progress' => true,
            'goal_reminder_frequency' => 'weekly',
        ]);
    }

    // Helper methods
    public function getPrimaryResumeAttribute()
    {
        return $this->userDocuments()
            ->where('document_type', 'resume')
            ->where('is_primary', true)
            ->first();
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo_path ?
            asset('storage/'.$this->profile_photo_path) : null;
    }

    public function updateProfileCompletion(): void
    {
        $fields = [
            'name', 'email', 'phone', 'location', 'bio', 'current_title',
            'current_company', 'years_experience', 'target_roles', 'skills',
            'headline', 'professional_summary', 'work_experience', 'education',
            'projects', 'certifications', 'languages', 'awards', 'publications',
            'volunteer_work', 'interests', 'target_industries', 'linkedin_url',
            'github_url', 'portfolio_url'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (! empty($this->$field)) {
                $completed++;
            }
        }

        $percentage = round(($completed / count($fields)) * 100);
        $this->update(['profile_completion_percentage' => $percentage]);
    }

    public function needsOnboarding(): bool
    {
        return ! $this->onboarding_completed;
    }

    public function getOverallMasteryScore(): float
    {
        // Try to get average from mastery scores first
        $masteryScoreAvg = $this->masteryScores()->avg('score');

        if ($masteryScoreAvg !== null) {
            return round($masteryScoreAvg, 2);
        }

        // Fallback to mastery topics average if no mastery scores
        $masteryTopicAvg = $this->masteryTopics()->avg('mastery_level');

        if ($masteryTopicAvg !== null) {
            // Convert mastery level (1-5 scale) to percentage (0-100 scale)
            return round(($masteryTopicAvg / 5) * 100, 2);
        }

        // Return 0 if no mastery data exists
        return 0.0;
    }
}
