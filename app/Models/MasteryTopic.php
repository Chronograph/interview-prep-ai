<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasteryTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic_name',
        'category',
        'description',
        'mastery_level',
        'total_attempts',
        'average_score',
        'best_score',
        'recent_score',
        'score_history',
        'last_practiced_at',
        'practice_streak',
        'strengths',
        'weaknesses',
        'improvement_suggestions',
        'is_priority',
    ];

    protected $casts = [
        'average_score' => 'decimal:2',
        'best_score' => 'decimal:2',
        'recent_score' => 'decimal:2',
        'score_history' => 'array',
        'last_practiced_at' => 'datetime',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'improvement_suggestions' => 'array',
        'is_priority' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addScore(float $score): void
    {
        $this->increment('total_attempts');
        
        // Update scores
        $this->update([
            'recent_score' => $score,
            'best_score' => max($this->best_score ?? 0, $score),
            'last_practiced_at' => now(),
        ]);

        // Update average score
        if ($this->average_score === null) {
            $this->update(['average_score' => $score]);
        } else {
            $newAverage = (($this->average_score * ($this->total_attempts - 1)) + $score) / $this->total_attempts;
            $this->update(['average_score' => round($newAverage, 2)]);
        }

        // Update score history (keep last 10 scores)
        $history = $this->score_history ?? [];
        $history[] = [
            'score' => $score,
            'date' => now()->toDateString(),
        ];
        
        if (count($history) > 10) {
            $history = array_slice($history, -10);
        }
        
        $this->update(['score_history' => $history]);

        // Update mastery level based on performance
        $this->updateMasteryLevel();
        
        // Update practice streak
        $this->updatePracticeStreak();
    }

    protected function updateMasteryLevel(): void
    {
        if ($this->total_attempts < 3) return;

        $newLevel = match(true) {
            $this->average_score >= 4.5 => 5,
            $this->average_score >= 3.5 => 4,
            $this->average_score >= 2.5 => 3,
            $this->average_score >= 1.5 => 2,
            default => 1
        };

        $this->update(['mastery_level' => $newLevel]);
    }

    protected function updatePracticeStreak(): void
    {
        if ($this->last_practiced_at === null) {
            $this->update(['practice_streak' => 1]);
            return;
        }

        $daysSinceLastPractice = $this->last_practiced_at->diffInDays(now());
        
        if ($daysSinceLastPractice <= 1) {
            $this->increment('practice_streak');
        } else {
            $this->update(['practice_streak' => 1]);
        }
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeNeedsWork($query)
    {
        return $query->where('mastery_level', '<=', 2)
                    ->orWhere('average_score', '<', 3.0);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('is_priority', true);
    }

    public function getMasteryDisplayAttribute(): string
    {
        return match($this->mastery_level) {
            1 => 'Beginner',
            2 => 'Developing',
            3 => 'Competent',
            4 => 'Proficient',
            5 => 'Expert',
            default => 'Unknown'
        };
    }

    public function getCategoryDisplayAttribute(): string
    {
        return match($this->category) {
            'behavioral' => 'Behavioral',
            'technical' => 'Technical',
            'communication' => 'Communication',
            'leadership' => 'Leadership',
            'industry_specific' => 'Industry-Specific',
            default => ucfirst($this->category)
        };
    }
}