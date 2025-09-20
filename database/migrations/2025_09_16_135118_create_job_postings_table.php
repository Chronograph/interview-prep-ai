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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('company');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('location')->nullable();
            $table->string('job_type')->nullable(); // full-time, part-time, contract, etc.
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('experience_level')->nullable(); // entry, mid, senior
            $table->json('skills')->nullable(); // array of required skills
            $table->string('source_url')->nullable(); // original job posting URL
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
