<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $fillable = [
        'interview_id',
        'interview_question_id',
        'feedback_type',
        'summary',
        'strengths',
        'areas_for_improvement',
        'specific_suggestions',
        'scores',
        'speaking_analysis',
        'content_analysis',
        'follow_up_questions',
        'confidence_level',
    ];

    protected $casts = [
        'strengths' => 'array',
        'areas_for_improvement' => 'array',
        'specific_suggestions' => 'array',
        'scores' => 'array',
        'speaking_analysis' => 'array',
        'content_analysis' => 'array',
        'follow_up_questions' => 'array',
        'confidence_level' => 'decimal:2',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function interviewQuestion(): BelongsTo
    {
        return $this->belongsTo(InterviewQuestion::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('feedback_type', $type);
    }

    public function scopeOverall($query)
    {
        return $query->where('feedback_type', 'overall');
    }

    public function scopeQuestionSpecific($query)
    {
        return $query->where('feedback_type', 'question_specific');
    }
}
