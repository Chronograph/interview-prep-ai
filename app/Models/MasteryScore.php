<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasteryScore extends Model
{
    protected $fillable = [
        'user_id',
        'topic',
        'skill',
        'score',
        'attempts',
        'improvement_rate',
        'last_practiced_at',
        'performance_history',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'improvement_rate' => 'decimal:2',
        'last_practiced_at' => 'datetime',
        'performance_history' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateScore(float $newScore): void
    {
        $history = $this->performance_history ?? [];
        $history[] = [
            'score' => $this->score,
            'date' => now()->toISOString(),
        ];

        $this->update([
            'score' => $newScore,
            'attempts' => $this->attempts + 1,
            'improvement_rate' => $this->calculateImprovementRate($newScore),
            'last_practiced_at' => now(),
            'performance_history' => $history,
        ]);
    }

    private function calculateImprovementRate(float $newScore): float
    {
        if ($this->attempts === 0) {
            return 0;
        }

        $oldScore = $this->score;
        return $oldScore > 0 ? (($newScore - $oldScore) / $oldScore) * 100 : 0;
    }
}
