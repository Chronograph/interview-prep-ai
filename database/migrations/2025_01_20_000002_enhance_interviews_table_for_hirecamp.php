<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            // Interview configuration
            $table->string('difficulty_level')->default('medium')->after('interview_type'); // easy, medium, hard
            $table->boolean('is_panel_interview')->default(false)->after('difficulty_level');
            $table->json('ai_personas')->nullable()->after('is_panel_interview'); // For panel interviews
            $table->string('focus_area')->nullable()->after('ai_personas'); // elevator_pitch, technical_skills, etc.
            
            // Recording and media
            $table->string('audio_path')->nullable()->after('video_path');
            $table->json('recording_metadata')->nullable()->after('audio_path'); // Quality, duration, etc.
            
            // Scoring and feedback
            $table->decimal('communication_score', 3, 2)->nullable()->after('overall_score');
            $table->decimal('technical_score', 3, 2)->nullable()->after('communication_score');
            $table->decimal('confidence_score', 3, 2)->nullable()->after('technical_score');
            $table->decimal('clarity_score', 3, 2)->nullable()->after('confidence_score');
            $table->decimal('pace_score', 3, 2)->nullable()->after('clarity_score');
            
            // Audio/Video quality assessment
            $table->string('audio_quality')->nullable()->after('pace_score'); // poor, ok, good, professional
            $table->string('video_quality')->nullable()->after('audio_quality'); // poor, ok, good, professional
            $table->string('lighting_quality')->nullable()->after('video_quality'); // poor, ok, good, professional
            $table->string('background_quality')->nullable()->after('lighting_quality'); // distracting, ok, good, professional
            $table->boolean('has_background_noise')->default(false)->after('background_quality');
            
            // AI analysis
            $table->json('speech_analysis')->nullable()->after('has_background_noise'); // Speed, pauses, filler words
            $table->json('strengths')->nullable()->after('speech_analysis'); // Array of identified strengths
            $table->json('weaknesses')->nullable()->after('strengths'); // Array of areas for improvement
            $table->json('improvement_suggestions')->nullable()->after('weaknesses'); // Specific suggestions
            
            // Practice tracking
            $table->boolean('is_practice')->default(true)->after('improvement_suggestions'); // vs real interview prep
            $table->string('practice_type')->nullable()->after('is_practice'); // general, company_specific, skill_specific
        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn([
                'difficulty_level', 'is_panel_interview', 'ai_personas', 'focus_area',
                'audio_path', 'recording_metadata', 'communication_score', 'technical_score',
                'confidence_score', 'clarity_score', 'pace_score', 'audio_quality',
                'video_quality', 'lighting_quality', 'background_quality', 'has_background_noise',
                'speech_analysis', 'strengths', 'weaknesses', 'improvement_suggestions',
                'is_practice', 'practice_type'
            ]);
        });
    }
};