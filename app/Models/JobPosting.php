<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'description',
        'requirements',
        'location',
        'job_type',
        'salary_min',
        'salary_max',
        'experience_level',
        'skills',
        'source_url',
        'is_active',
    ];

    protected $casts = [
        'skills' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
