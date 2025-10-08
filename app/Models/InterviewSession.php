<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class InterviewSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'interview_id',
        'job_posting_id',
        'ai_persona_id',
        'session_type',
        'focus_area',
        'difficulty_level',
        'is_panel_interview',
        'ai_personas_used',
        'session_config',
        'planned_duration_minutes',
        'actual_duration_minutes',
        'questions_planned',
        'questions_completed',
        'video_path',
        'audio_path',
        'media_metadata',
        'overall_score',
        'communication_score',
        'technical_score',
        'confidence_score',
        'clarity_score',
        'pace_score',
        'engagement_score',
        'audio_quality',
        'video_quality',
        'lighting_quality',
        'background_quality',
        'has_background_noise',
        'speech_analysis',
        'strengths',
        'weaknesses',
        'improvement_suggestions',
        'ai_summary',
        'status',
        'started_at',
        'completed_at',
        'is_practice',
    ];

    protected $casts = [
        'ai_personas_used' => 'array',
        'session_config' => 'array',
        'media_metadata' => 'array',
        'overall_score' => 'decimal:2',
        'communication_score' => 'decimal:2',
        'technical_score' => 'decimal:2',
        'confidence_score' => 'decimal:2',
        'clarity_score' => 'decimal:2',
        'pace_score' => 'decimal:2',
        'engagement_score' => 'decimal:2',
        'has_background_noise' => 'boolean',
        'speech_analysis' => 'array',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'improvement_suggestions' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_practice' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function aiPersona(): BelongsTo
    {
        return $this->belongsTo(AiPersona::class);
    }

    public function getVideoUrlAttribute(): ?string
    {
        return $this->video_path ? Storage::url($this->video_path) : null;
    }

    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio_path ? Storage::url($this->audio_path) : null;
    }

    public function start(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'actual_duration_minutes' => $this->started_at ?
                $this->started_at->diffInMinutes(now()) : null,
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getCompletionPercentageAttribute(): float
    {
        if (! $this->questions_planned || $this->questions_planned === 0) {
            return 0;
        }

        return round(($this->questions_completed / $this->questions_planned) * 100, 1);
    }

    public function getAverageScoreAttribute(): ?float
    {
        $scores = array_filter([
            $this->communication_score,
            $this->technical_score,
            $this->confidence_score,
            $this->clarity_score,
            $this->pace_score,
            $this->engagement_score,
        ]);

        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopePractice($query)
    {
        return $query->where('is_practice', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('session_type', $type);
    }

    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function getSessionTypeDisplayAttribute(): string
    {
        return match ($this->session_type) {
            'full_interview' => 'Full Interview',
            'topic_practice' => 'Topic Practice',
            'elevator_pitch' => 'Elevator Pitch',
            'behavioral' => 'Behavioral Questions',
            'technical' => 'Technical Questions',
            'company_questions' => 'Company Questions',
            default => ucfirst(str_replace('_', ' ', $this->session_type))
        };
    }

    public function getDifficultyDisplayAttribute(): string
    {
        return match ($this->difficulty_level) {
            'easy' => 'Easy',
            'medium' => 'Medium',
            'hard' => 'Hard',
            default => ucfirst($this->difficulty_level)
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'Scheduled',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status)
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($session) {
            // Delete media files when session is deleted
            if ($session->video_path && Storage::exists($session->video_path)) {
                Storage::delete($session->video_path);
            }
            if ($session->audio_path && Storage::exists($session->audio_path)) {
                Storage::delete($session->audio_path);
            }
        });
    }
}
