<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->boolean('email_goal_reminders')->default(true)->after('score_improvement_target');
            $table->boolean('email_weekly_progress')->default(true)->after('email_goal_reminders');
            $table->string('goal_reminder_frequency')->default('weekly')->after('email_weekly_progress'); // daily, weekly, bi-weekly
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn(['email_goal_reminders', 'email_weekly_progress', 'goal_reminder_frequency']);
        });
    }
};
