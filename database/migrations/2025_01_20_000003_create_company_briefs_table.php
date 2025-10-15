<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_briefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->text('company_description')->nullable();
            $table->text('company_mission')->nullable();
            $table->json('key_products_services')->nullable(); // Array of products/services
            $table->json('competitors')->nullable(); // Array of competitor names
            $table->json('recent_news')->nullable(); // Array of recent news items
            $table->json('company_culture')->nullable(); // Culture highlights
            $table->json('values')->nullable(); // Company values
            $table->text('industry')->nullable();
            $table->string('company_size')->nullable(); // startup, small, medium, large, enterprise
            $table->string('funding_stage')->nullable(); // seed, series_a, etc.
            $table->decimal('valuation', 15, 2)->nullable();
            $table->json('leadership_team')->nullable(); // Key executives
            $table->json('talking_points')->nullable(); // AI-generated talking points
            $table->json('potential_questions')->nullable(); // Questions they might ask
            $table->json('why_work_here')->nullable(); // Reasons to work at this company
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'company_name']);
            // Index with limited key length for industry column
            $table->index(['company_name'], 'company_briefs_company_name_index');
        });

        // Create index with limited key length for industry column
        DB::statement('CREATE INDEX company_briefs_industry_index ON company_briefs (industry(191))');
    }

    public function down(): void
    {
        Schema::dropIfExists('company_briefs');
    }
};
