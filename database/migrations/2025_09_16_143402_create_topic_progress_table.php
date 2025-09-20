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
        Schema::create('topic_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('topic_name'); // e.g., 'Data Structures', 'System Design'
            $table->string('category'); // e.g., 'Technical', 'Behavioral', 'Case Study'
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->decimal('completion_percentage', 5, 2)->default(0); // 0-100%
            $table->integer('questions_attempted')->default(0);
            $table->integer('questions_correct')->default(0);
            $table->decimal('average_score', 5, 2)->default(0);
            $table->integer('time_spent_minutes')->default(0); // Total time spent on topic
            $table->json('strengths')->nullable(); // Array of identified strengths
            $table->json('weaknesses')->nullable(); // Array of areas for improvement
            $table->timestamp('last_practiced_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'topic_name']);
            $table->index(['user_id', 'category']);
            $table->index(['completion_percentage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_progress');
    }
};
