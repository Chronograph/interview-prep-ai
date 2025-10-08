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
        Schema::table('interview_sessions', function (Blueprint $table) {
            $table->foreignId('ai_persona_id')->nullable()->constrained('ai_personas')->onDelete('set null')->after('job_posting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_sessions', function (Blueprint $table) {
            $table->dropForeign(['ai_persona_id']);
            $table->dropColumn('ai_persona_id');
        });
    }
};
