<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Interview extends Model
{
    protected $fillable = [
        'user_id',
        'job_posting_id',
        'resume_id',
        'title',
        'description',
        'status',
        'interview_type',
        'duration_minutes',
        'started_at',
        'completed_at',
        'video_path',
        'overall_score',
        'ai_context',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'overall_score' => 'decimal:2',
        'ai_context' => 'array',
        'duration_minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(InterviewQuestion::class)->orderBy('question_order');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function getActualDurationAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }
        return null;
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
