<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'position_title',
        'job_url',
        'status',
        'priority',
        'application_date',
        'expected_response_date',
        'salary_min',
        'salary_max',
        'location',
        'work_type',
        'notes',
        'interview_stages',
        'contacts',
        'requirements',
        'is_favorite',
    ];

    protected $casts = [
        'application_date' => 'date',
        'expected_response_date' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'interview_stages' => 'array',
        'contacts' => 'array',
        'requirements' => 'array',
        'is_favorite' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatus(string $newStatus): void
    {
        $stages = $this->interview_stages ?? [];
        $stages[] = [
            'status' => $this->status,
            'changed_at' => now()->toISOString(),
            'new_status' => $newStatus,
        ];

        $this->update([
            'status' => $newStatus,
            'interview_stages' => $stages,
        ]);
    }

    public function addContact(string $name, string $email, string $role = null): void
    {
        $contacts = $this->contacts ?? [];
        $contacts[] = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'added_at' => now()->toISOString(),
        ];

        $this->update(['contacts' => $contacts]);
    }

    public function getDaysUntilResponse(): ?int
    {
        if (!$this->expected_response_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->expected_response_date, false);
    }

    public function isOverdue(): bool
    {
        if (!$this->expected_response_date) {
            return false;
        }

        return Carbon::now()->isAfter($this->expected_response_date);
    }

    public function getSalaryRange(): ?string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return null;
        }

        if ($this->salary_min && $this->salary_max) {
            return '$' . number_format($this->salary_min) . ' - $' . number_format($this->salary_max);
        }

        return $this->salary_min 
            ? '$' . number_format($this->salary_min) . '+'
            : 'Up to $' . number_format($this->salary_max);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['rejected', 'withdrawn']);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }
}
