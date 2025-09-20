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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->default('My Resume');
            $table->text('summary')->nullable();
            $table->json('experience')->nullable(); // array of work experience
            $table->json('education')->nullable(); // array of education
            $table->json('skills')->nullable(); // array of skills
            $table->json('certifications')->nullable(); // array of certifications
            $table->json('projects')->nullable(); // array of projects
            $table->text('raw_content')->nullable(); // extracted text from uploaded file
            $table->string('file_path')->nullable(); // path to uploaded resume file
            $table->string('file_type')->nullable(); // pdf, docx, etc.
            $table->boolean('is_primary')->default(false); // primary resume for user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
