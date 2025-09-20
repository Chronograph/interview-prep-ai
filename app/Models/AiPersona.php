<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPersona extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role_title',
        'department',
        'personality_description',
        'interview_style',
        'question_types',
        'focus_areas',
        'background',
        'typical_questions',
        'ai_prompt_template',
        'difficulty_level',
        'is_active',
        'is_default',
        'usage_count',
        'average_rating',
    ];

    protected $casts = [
        'question_types' => 'array',
        'focus_areas' => 'array',
        'typical_questions' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'average_rating' => 'decimal:2',
    ];

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function addRating(float $rating): void
    {
        if ($this->average_rating === null) {
            $this->update(['average_rating' => $rating]);
        } else {
            // Simple moving average - could be improved with weighted average
            $newAverage = (($this->average_rating * $this->usage_count) + $rating) / ($this->usage_count + 1);
            $this->update(['average_rating' => round($newAverage, 2)]);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    public function scopeHighRated($query)
    {
        return $query->whereNotNull('average_rating')
                    ->orderBy('average_rating', 'desc');
    }

    public function getDifficultyDisplayAttribute(): string
    {
        return match($this->difficulty_level) {
            'easy' => 'Easy',
            'medium' => 'Medium',
            'hard' => 'Hard',
            default => ucfirst($this->difficulty_level)
        };
    }

    public function getInterviewStyleDisplayAttribute(): string
    {
        return match($this->interview_style) {
            'friendly' => 'Friendly & Supportive',
            'tough' => 'Challenging & Direct',
            'analytical' => 'Analytical & Detail-Oriented',
            'conversational' => 'Conversational & Relaxed',
            default => ucfirst($this->interview_style)
        };
    }

    public function getDepartmentDisplayAttribute(): string
    {
        return match($this->department) {
            'engineering' => 'Engineering',
            'hr' => 'Human Resources',
            'product' => 'Product Management',
            'sales' => 'Sales',
            'marketing' => 'Marketing',
            'finance' => 'Finance',
            'operations' => 'Operations',
            'executive' => 'Executive Leadership',
            default => ucfirst($this->department)
        };
    }

    public function getSystemPrompt(array $context = []): string
    {
        $prompt = $this->ai_prompt_template;
        
        // Replace placeholders with context
        foreach ($context as $key => $value) {
            $prompt = str_replace("{{$key}}", $value, $prompt);
        }
        
        return $prompt;
    }
}