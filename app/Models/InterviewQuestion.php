<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterviewQuestion extends Model
{
    protected $fillable = [
        'interview_id',
        'question_order',
        'question',
        'user_response',
        'ai_follow_up',
        'question_type',
        'response_time_seconds',
        'question_score',
        'analysis',
        'asked_at',
        'answered_at',
    ];

    protected $casts = [
        'analysis' => 'array',
        'question_score' => 'decimal:2',
        'response_time_seconds' => 'integer',
        'asked_at' => 'datetime',
        'answered_at' => 'datetime',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('question_order');
    }

    public function scopeAnswered($query)
    {
        return $query->whereNotNull('user_response');
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('user_response');
    }
}
