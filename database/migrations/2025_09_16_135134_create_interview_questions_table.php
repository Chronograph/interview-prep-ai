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
        Schema::create('interview_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->integer('question_order');
            $table->text('question');
            $table->text('user_response')->nullable();
            $table->text('ai_follow_up')->nullable();
            $table->enum('question_type', ['behavioral', 'technical', 'situational', 'general']);
            $table->integer('response_time_seconds')->nullable();
            $table->decimal('question_score', 5, 2)->nullable(); // 0-100 score for this question
            $table->json('analysis')->nullable(); // AI analysis of the response
            $table->timestamp('asked_at')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            
            $table->index(['interview_id', 'question_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_questions');
    }
};
