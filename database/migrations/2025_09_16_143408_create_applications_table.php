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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('position_title');
            $table->string('job_url')->nullable();
            $table->enum('status', [
                'applied', 'screening', 'phone_interview', 'technical_interview',
                'onsite_interview', 'final_interview', 'offer', 'rejected', 'withdrawn'
            ])->default('applied');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->date('application_date');
            $table->date('expected_response_date')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->enum('work_type', ['remote', 'hybrid', 'onsite'])->nullable();
            $table->text('notes')->nullable();
            $table->json('interview_stages')->nullable(); // Track interview progress
            $table->json('contacts')->nullable(); // Store recruiter/contact info
            $table->json('requirements')->nullable(); // Job requirements
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'application_date']);
            $table->index(['user_id', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
