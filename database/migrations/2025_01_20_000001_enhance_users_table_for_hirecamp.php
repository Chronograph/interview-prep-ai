<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profile information
            $table->string('phone')->nullable()->after('email');
            $table->text('bio')->nullable()->after('phone');
            $table->string('location')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('location');
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
            $table->string('github_url')->nullable()->after('portfolio_url');
            $table->string('personal_website')->nullable()->after('github_url');
            
            // Career information
            $table->string('current_role')->nullable()->after('personal_website');
            $table->string('experience_level')->nullable()->after('current_role'); // entry, mid, senior, executive
            $table->json('target_roles')->nullable()->after('experience_level'); // Array of roles they're targeting
            $table->json('skills')->nullable()->after('target_roles'); // Array of skills
            $table->decimal('target_salary_min', 10, 2)->nullable()->after('skills');
            $table->decimal('target_salary_max', 10, 2)->nullable()->after('target_salary_min');
            
            // Interview preferences
            $table->json('preferred_interview_types')->nullable()->after('target_salary_max'); // video, audio, both
            $table->integer('practice_sessions_completed')->default(0)->after('preferred_interview_types');
            $table->decimal('overall_interview_score', 3, 2)->nullable()->after('practice_sessions_completed');
            
            // Onboarding and settings
            $table->boolean('onboarding_completed')->default(false)->after('overall_interview_score');
            $table->json('notification_preferences')->nullable()->after('onboarding_completed');
            $table->timestamp('last_active_at')->nullable()->after('notification_preferences');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'bio', 'location', 'linkedin_url', 'portfolio_url', 
                'github_url', 'personal_website', 'current_role', 'experience_level',
                'target_roles', 'skills', 'target_salary_min', 'target_salary_max',
                'preferred_interview_types', 'practice_sessions_completed', 
                'overall_interview_score', 'onboarding_completed', 
                'notification_preferences', 'last_active_at'
            ]);
        });
    }
};