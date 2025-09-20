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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_posting_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('resume_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('interview_type', ['behavioral', 'technical', 'mixed'])->default('mixed');
            $table->integer('duration_minutes')->default(30);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('video_path')->nullable(); // path to recorded video
            $table->decimal('overall_score', 5, 2)->nullable(); // 0-100 score
            $table->json('ai_context')->nullable(); // AI conversation context
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
