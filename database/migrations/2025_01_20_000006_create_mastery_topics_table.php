<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mastery_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('topic_name'); // e.g., "Behavioral Questions", "System Design", "Leadership"
            $table->string('category'); // behavioral, technical, communication, leadership, industry_specific
            $table->text('description')->nullable();
            $table->integer('mastery_level')->default(1); // 1-5 scale
            $table->integer('total_attempts')->default(0);
            $table->decimal('average_score', 3, 2)->nullable(); // Average performance
            $table->decimal('best_score', 3, 2)->nullable(); // Best performance
            $table->decimal('recent_score', 3, 2)->nullable(); // Most recent performance
            $table->json('score_history')->nullable(); // Array of recent scores
            $table->timestamp('last_practiced_at')->nullable();
            $table->integer('practice_streak')->default(0); // Days in a row practiced
            $table->json('strengths')->nullable(); // What user does well
            $table->json('weaknesses')->nullable(); // Areas for improvement
            $table->json('improvement_suggestions')->nullable(); // AI suggestions
            $table->boolean('is_priority')->default(false); // User marked as priority to work on
            $table->timestamps();
            
            $table->unique(['user_id', 'topic_name']);
            $table->index(['user_id', 'category']);
            $table->index(['mastery_level', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mastery_topics');
    }
};