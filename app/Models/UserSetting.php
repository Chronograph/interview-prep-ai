<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'job_applications_per_week',
        'practice_interviews_per_week',
        'score_improvement_target',
        'email_goal_reminders',
        'email_weekly_progress',
        'goal_reminder_frequency',
    ];

    protected $casts = [
        'job_applications_per_week' => 'integer',
        'practice_interviews_per_week' => 'integer',
        'score_improvement_target' => 'integer',
        'email_goal_reminders' => 'boolean',
        'email_weekly_progress' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
