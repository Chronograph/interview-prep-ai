<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // User-friendly name
            $table->string('document_type'); // resume, portfolio, cover_letter, transcript, certificate, other
            $table->string('file_path'); // Storage path
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('file_size'); // in bytes
            $table->json('parsed_content')->nullable(); // AI-extracted content
            $table->json('key_skills')->nullable(); // Extracted skills
            $table->json('experience_highlights')->nullable(); // Key experiences
            $table->json('education_details')->nullable(); // Education info
            $table->json('achievements')->nullable(); // Notable achievements
            $table->text('ai_summary')->nullable(); // AI-generated summary
            $table->boolean('is_primary')->default(false); // Primary resume/portfolio
            $table->boolean('is_active')->default(true); // Currently using
            $table->timestamp('last_analyzed_at')->nullable(); // When AI last processed
            $table->integer('usage_count')->default(0); // How often used in applications
            $table->timestamps();
            
            $table->index(['user_id', 'document_type']);
            $table->index(['user_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};