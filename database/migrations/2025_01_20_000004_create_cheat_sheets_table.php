<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cheat_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // e.g., "Tell me about yourself", "Why do you want this job?"
            $table->string('category'); // behavioral, technical, company_specific, general
            $table->text('topic_description')->nullable();
            $table->json('key_points')->nullable(); // Main points to cover
            $table->text('suggested_response')->nullable(); // AI-generated response template
            $table->json('examples')->nullable(); // Specific examples from user's background
            $table->json('do_say')->nullable(); // Things to include
            $table->json('dont_say')->nullable(); // Things to avoid
            $table->json('follow_up_questions')->nullable(); // Potential follow-ups
            $table->integer('usage_count')->default(0); // How often user has practiced this
            $table->decimal('average_score', 3, 2)->nullable(); // Average performance on this topic
            $table->timestamp('last_practiced_at')->nullable();
            $table->boolean('is_custom')->default(false); // User-created vs AI-generated
            $table->timestamps();
            
            $table->index(['user_id', 'category']);
            $table->index(['category', 'usage_count']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cheat_sheets');
    }
};