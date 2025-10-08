<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Resume extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'parent_resume_id',
        'title',
        'version',
        // Personal Information
        'full_name',
        'email',
        'phone',
        'location',
        'linkedin_url',
        'portfolio_url',
        'github_url',
        // Professional Details
        'headline',
        'summary',
        'objective',
        'experience',
        'education',
        'skills',
        'certifications',
        'projects',
        'languages',
        'awards',
        'publications',
        'volunteer_work',
        'references',
        'interests',
        // File Information
        'raw_content',
        'file_path',
        'file_type',
        'file_size',
        // Metadata
        'is_primary',
        'optimization_score',
        'optimized_companies',
        'optimized_roles',
    ];

    protected $casts = [
        'experience' => 'array',
        'education' => 'array',
        'skills' => 'array',
        'certifications' => 'array',
        'projects' => 'array',
        'languages' => 'array',
        'awards' => 'array',
        'publications' => 'array',
        'volunteer_work' => 'array',
        'references' => 'array',
        'interests' => 'array',
        'optimized_companies' => 'array',
        'optimized_roles' => 'array',
        'is_primary' => 'boolean',
        'version' => 'integer',
        'optimization_score' => 'integer',
        'file_size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function parentResume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'parent_resume_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Resume::class, 'parent_resume_id');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Create a new version of this resume
     */
    public function createVersion(array $data = []): self
    {
        $parentId = $this->parent_resume_id ?? $this->id;
        $latestVersion = Resume::where('parent_resume_id', $parentId)
            ->orWhere('id', $parentId)
            ->max('version') ?? $this->version;

        $newResume = $this->replicate();
        $newResume->parent_resume_id = $parentId;
        $newResume->version = $latestVersion + 1;
        $newResume->is_primary = false;
        $newResume->fill($data);
        $newResume->save();

        return $newResume;
    }

    /**
     * Get all versions of this resume including itself
     */
    public function getAllVersions()
    {
        $parentId = $this->parent_resume_id ?? $this->id;

        return Resume::where('id', $parentId)
            ->orWhere('parent_resume_id', $parentId)
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get performance metrics for this resume
     */
    public function getPerformanceMetrics(): array
    {
        $applications = $this->applications;
        $totalApplications = $applications->count();
        $responsesReceived = $applications->where('got_response', true)->count();
        $interviews = $this->interviews->count();

        return [
            'applications' => $totalApplications,
            'response_rate' => $totalApplications > 0
                ? round(($responsesReceived / $totalApplications) * 100)
                : 0,
            'interviews' => $interviews,
        ];
    }

    /**
     * Calculate optimization score based on completeness
     */
    public function calculateOptimizationScore(): int
    {
        $score = 0;

        // Essential fields (60 points)
        if ($this->title) {
            $score += 5;
        }
        if ($this->full_name) {
            $score += 5;
        }
        if ($this->email) {
            $score += 5;
        }
        if ($this->phone) {
            $score += 5;
        }
        if ($this->summary) {
            $score += 15;
        }
        if ($this->skills && count($this->skills) > 0) {
            $score += 15;
        }
        if ($this->experience && count($this->experience) > 0) {
            $score += 10;
        }

        // Important fields (25 points)
        if ($this->headline) {
            $score += 5;
        }
        if ($this->location) {
            $score += 3;
        }
        if ($this->education && count($this->education) > 0) {
            $score += 7;
        }
        if ($this->projects && count($this->projects) > 0) {
            $score += 5;
        }
        if ($this->certifications && count($this->certifications) > 0) {
            $score += 5;
        }

        // Nice to have fields (15 points)
        if ($this->linkedin_url) {
            $score += 3;
        }
        if ($this->portfolio_url || $this->github_url) {
            $score += 3;
        }
        if ($this->objective) {
            $score += 3;
        }
        if ($this->languages && count($this->languages) > 0) {
            $score += 2;
        }
        if ($this->awards && count($this->awards) > 0) {
            $score += 2;
        }
        if ($this->volunteer_work && count($this->volunteer_work) > 0) {
            $score += 2;
        }

        return min($score, 100); // Cap at 100
    }

    /**
     * Update optimization score
     */
    public function updateOptimizationScore(): void
    {
        $this->update(['optimization_score' => $this->calculateOptimizationScore()]);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (! $this->file_size) {
            return 'N/A';
        }

        if ($this->file_size < 1024) {
            return $this->file_size.' KB';
        }

        return round($this->file_size / 1024, 1).' MB';
    }
}
