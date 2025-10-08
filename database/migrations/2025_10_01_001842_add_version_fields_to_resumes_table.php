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
        Schema::table('resumes', function (Blueprint $table) {
            $table->integer('version')->default(1)->after('title');
            $table->foreignId('parent_resume_id')->nullable()->after('user_id')->constrained('resumes')->onDelete('set null');
            $table->integer('optimization_score')->default(0)->after('is_primary');
            $table->json('optimized_companies')->nullable()->after('optimization_score');
            $table->json('optimized_roles')->nullable()->after('optimized_companies');
            $table->integer('file_size')->nullable()->after('file_type'); // in KB
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropForeign(['parent_resume_id']);
            $table->dropColumn([
                'version',
                'parent_resume_id',
                'optimization_score',
                'optimized_companies',
                'optimized_roles',
                'file_size',
            ]);
        });
    }
};
