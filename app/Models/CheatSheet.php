<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheatSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_posting_id',
        'title',
        'category',
        'topic_description',
        'key_points',
        'suggested_response',
        'examples',
        'do_say',
        'dont_say',
        'follow_up_questions',
        'usage_count',
        'average_score',
        'last_practiced_at',
        'is_custom',
        'interview_date',
    ];

    protected $casts = [
        'key_points' => 'array',
        'examples' => 'array',
        'do_say' => 'array',
        'dont_say' => 'array',
        'follow_up_questions' => 'array',
        'average_score' => 'decimal:2',
        'last_practiced_at' => 'datetime',
        'is_custom' => 'boolean',
        'interview_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
        $this->update(['last_practiced_at' => now()]);
    }

    public function updateScore(float $score): void
    {
        if ($this->average_score === null) {
            $this->update(['average_score' => $score]);
        } else {
            $newAverage = (($this->average_score * $this->usage_count) + $score) / ($this->usage_count + 1);
            $this->update(['average_score' => round($newAverage, 2)]);
        }
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeMostPracticed($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    public function scopeNeedsPractice($query)
    {
        return $query->where(function ($q) {
            $q->where('average_score', '<', 3.0)
                ->orWhere('usage_count', '<', 3)
                ->orWhereNull('last_practiced_at')
                ->orWhere('last_practiced_at', '<', now()->subWeeks(2));
        });
    }

    public function getCategoryDisplayAttribute(): string
    {
        return match ($this->category) {
            'behavioral' => 'Behavioral Questions',
            'technical' => 'Technical Questions',
            'company_specific' => 'Company-Specific',
            'general' => 'General Interview',
            default => ucfirst($this->category)
        };
    }

    public static function getCategories(): array
    {
        return [
            'behavioral' => 'Behavioral Questions',
            'technical' => 'Technical Questions',
            'company_specific' => 'Company-Specific',
            'general' => 'General Interview',
            'custom' => 'Custom',
        ];
    }
}
