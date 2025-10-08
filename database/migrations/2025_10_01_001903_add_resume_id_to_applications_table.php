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
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('resume_id')->nullable()->after('user_id')->constrained('resumes')->onDelete('set null');
            $table->boolean('got_response')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['resume_id']);
            $table->dropColumn(['resume_id', 'got_response']);
        });
    }
};
