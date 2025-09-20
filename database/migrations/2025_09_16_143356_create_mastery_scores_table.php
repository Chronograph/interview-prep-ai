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
        Schema::create('mastery_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('topic'); // e.g., 'JavaScript', 'System Design', 'Algorithms'
            $table->string('skill'); // e.g., 'Problem Solving', 'Communication', 'Technical Knowledge'
            $table->decimal('score', 5, 2)->default(0); // Score out of 100
            $table->integer('attempts')->default(0); // Number of times practiced
            $table->decimal('improvement_rate', 5, 2)->default(0); // Rate of improvement
            $table->timestamp('last_practiced_at')->nullable();
            $table->json('performance_history')->nullable(); // Store historical scores
            $table->timestamps();
            
            $table->unique(['user_id', 'topic', 'skill']);
            $table->index(['user_id', 'topic']);
            $table->index(['user_id', 'score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mastery_scores');
    }
};
