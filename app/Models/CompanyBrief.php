<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyBrief extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'company_mission',
        'key_products_services',
        'competitors',
        'recent_news',
        'company_culture',
        'values',
        'industry',
        'company_size',
        'funding_stage',
        'valuation',
        'leadership_team',
        'talking_points',
        'potential_questions',
        'why_work_here',
        'last_updated_at',
    ];

    protected $casts = [
        'key_products_services' => 'array',
        'competitors' => 'array',
        'recent_news' => 'array',
        'company_culture' => 'array',
        'values' => 'array',
        'leadership_team' => 'array',
        'talking_points' => 'array',
        'potential_questions' => 'array',
        'why_work_here' => 'array',
        'valuation' => 'decimal:2',
        'last_updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isStale(): bool
    {
        return $this->last_updated_at === null || 
               $this->last_updated_at->diffInDays(now()) > 30;
    }

    public function getCompanySizeDisplayAttribute(): string
    {
        return match($this->company_size) {
            'startup' => 'Startup (1-50 employees)',
            'small' => 'Small (51-200 employees)',
            'medium' => 'Medium (201-1000 employees)',
            'large' => 'Large (1001-5000 employees)',
            'enterprise' => 'Enterprise (5000+ employees)',
            default => 'Unknown size'
        };
    }
}