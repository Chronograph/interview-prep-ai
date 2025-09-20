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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->foreignId('interview_question_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('feedback_type', ['overall', 'question_specific', 'behavioral', 'technical']);
            $table->text('summary');
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('specific_suggestions')->nullable();
            $table->json('scores')->nullable(); // breakdown of different scoring categories
            $table->json('speaking_analysis')->nullable(); // pace, clarity, filler words, etc.
            $table->json('content_analysis')->nullable(); // relevance, structure, examples
            $table->text('follow_up_questions')->nullable(); // suggested practice questions
            $table->integer('confidence_level')->nullable(); // 1-10 scale
            $table->timestamps();
            
            $table->index(['interview_id', 'feedback_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
