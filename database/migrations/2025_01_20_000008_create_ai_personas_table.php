<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_personas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Senior Engineering Manager", "HR Director"
            $table->string('role_title'); // Their job title
            $table->string('department'); // engineering, hr, product, sales, etc.
            $table->text('personality_description'); // How they conduct interviews
            $table->string('interview_style'); // friendly, tough, analytical, conversational
            $table->json('question_types')->nullable(); // Types of questions they ask
            $table->json('focus_areas')->nullable(); // What they focus on (technical, cultural fit, etc.)
            $table->text('background')->nullable(); // Their professional background
            $table->json('typical_questions')->nullable(); // Common questions they ask
            $table->text('ai_prompt_template'); // System prompt for this persona
            $table->string('difficulty_level'); // easy, medium, hard
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false); // Default personas for new users
            $table->integer('usage_count')->default(0); // How often used
            $table->decimal('average_rating', 3, 2)->nullable(); // User ratings
            $table->timestamps();
            
            $table->index(['department', 'difficulty_level']);
            $table->index(['is_active', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_personas');
    }
};