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
        Schema::table('users', function (Blueprint $table) {
            // Professional Summary fields
            $table->string('headline')->nullable();
            $table->text('professional_summary')->nullable();
            $table->text('objective')->nullable();
            
            // Current position fields
            $table->string('current_title')->nullable();
            $table->string('current_company')->nullable();
            $table->integer('years_experience')->nullable();
            
            // Comprehensive profile fields
            $table->json('work_experience')->nullable();
            $table->json('education')->nullable();
            $table->json('projects')->nullable();
            $table->json('certifications')->nullable();
            $table->json('languages')->nullable();
            $table->json('awards')->nullable();
            $table->json('publications')->nullable();
            $table->json('volunteer_work')->nullable();
            $table->json('interests')->nullable();
            $table->json('target_industries')->nullable();
            
            // Profile photo
            $table->string('profile_photo_path')->nullable();
            
            // Profile completion
            $table->integer('profile_completion_percentage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'headline',
                'professional_summary',
                'objective',
                'current_title',
                'current_company',
                'years_experience',
                'work_experience',
                'education',
                'projects',
                'certifications',
                'languages',
                'awards',
                'publications',
                'volunteer_work',
                'interests',
                'target_industries',
                'profile_photo_path',
                'profile_completion_percentage',
            ]);
        });
    }
};
