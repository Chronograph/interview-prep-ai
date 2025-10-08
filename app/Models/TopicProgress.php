<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopicProgress extends Model
{
    protected $fillable = [
        'user_id',
        'topic_name',
        'category',
        'difficulty_level',
        'completion_percentage',
        'questions_attempted',
        'questions_correct',
        'average_score',
        'time_spent_minutes',
        'strengths',
        'weaknesses',
        'last_practiced_at',
    ];

    protected $casts = [
        'completion_percentage' => 'decimal:2',
        'average_score' => 'decimal:2',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'last_practiced_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recordAttempt(bool $isCorrect, float $score, int $timeSpent): void
    {
        $this->increment('questions_attempted');

        if ($isCorrect) {
            $this->increment('questions_correct');
        }

        $this->increment('time_spent_minutes', $timeSpent);

        // Recalculate average score
        $newAverage = (($this->average_score * ($this->questions_attempted - 1)) + $score) / $this->questions_attempted;

        $this->update([
            'average_score' => $newAverage,
            'completion_percentage' => $this->calculateCompletionPercentage(),
            'last_practiced_at' => now(),
        ]);
    }

    public function getAccuracyPercentage(): float
    {
        return $this->questions_attempted > 0
            ? ($this->questions_correct / $this->questions_attempted) * 100
            : 0;
    }

    public function calculateCompletionPercentage(): float
    {
        // This could be based on various factors like questions attempted,
        // time spent, accuracy, etc. For now, simple calculation based on attempts
        $maxQuestions = 50; // Configurable per topic

        return min(($this->questions_attempted / $maxQuestions) * 100, 100);
    }

    public function addStrength(string $strength): void
    {
        $strengths = $this->strengths ?? [];
        if (! in_array($strength, $strengths)) {
            $strengths[] = $strength;
            $this->update(['strengths' => $strengths]);
        }
    }

    public function addWeakness(string $weakness): void
    {
        $weaknesses = $this->weaknesses ?? [];
        if (! in_array($weakness, $weaknesses)) {
            $weaknesses[] = $weakness;
            $this->update(['weaknesses' => $weaknesses]);
        }
    }
}
