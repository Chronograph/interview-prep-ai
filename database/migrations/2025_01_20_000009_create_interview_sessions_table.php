<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('interview_id')->nullable(); // If part of formal interview - FK will be added later
            $table->unsignedBigInteger('job_posting_id')->nullable(); // If job-specific - FK will be added later
            $table->string('session_type'); // full_interview, topic_practice, elevator_pitch, etc.
            $table->string('focus_area')->nullable(); // What they're practicing
            $table->string('difficulty_level'); // easy, medium, hard
            $table->boolean('is_panel_interview')->default(false);
            $table->json('ai_personas_used')->nullable(); // Which personas were involved

            // Session configuration
            $table->integer('planned_duration_minutes')->nullable();
            $table->integer('actual_duration_minutes')->nullable();
            $table->integer('questions_planned')->nullable();
            $table->integer('questions_completed')->nullable();

            // Recording and media
            $table->string('video_path')->nullable();
            $table->string('audio_path')->nullable();
            $table->json('media_metadata')->nullable(); // Quality, format, etc.

            // Performance scores (1-5 scale)
            $table->decimal('overall_score', 3, 2)->nullable();
            $table->decimal('communication_score', 3, 2)->nullable();
            $table->decimal('technical_score', 3, 2)->nullable();
            $table->decimal('confidence_score', 3, 2)->nullable();
            $table->decimal('clarity_score', 3, 2)->nullable();
            $table->decimal('pace_score', 3, 2)->nullable();
            $table->decimal('engagement_score', 3, 2)->nullable();

            // Audio/Video quality assessment
            $table->string('audio_quality')->nullable(); // poor, ok, good, professional
            $table->string('video_quality')->nullable(); // poor, ok, good, professional
            $table->string('lighting_quality')->nullable(); // poor, ok, good, professional
            $table->string('background_quality')->nullable(); // distracting, ok, good, professional
            $table->boolean('has_background_noise')->default(false);

            // AI Analysis
            $table->json('speech_analysis')->nullable(); // Pace, filler words, etc.
            $table->json('strengths')->nullable(); // What they did well
            $table->json('weaknesses')->nullable(); // Areas for improvement
            $table->json('improvement_suggestions')->nullable(); // Specific recommendations
            $table->text('ai_summary')->nullable(); // Overall AI assessment

            // Session status
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_practice')->default(true); // vs real interview prep

            $table->timestamps();

            $table->index(['user_id', 'session_type']);
            $table->index(['user_id', 'status']);
            $table->index(['job_posting_id', 'session_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_sessions');
    }
};
