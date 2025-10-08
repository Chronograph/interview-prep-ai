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
        Schema::table('interviews', function (Blueprint $table) {
            // Only add columns that don't exist
            if (! Schema::hasColumn('interviews', 'location')) {
                $table->string('location')->nullable()->after('interview_time');
            }
            if (! Schema::hasColumn('interviews', 'readiness_score')) {
                $table->integer('readiness_score')->default(0)->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'readiness_score',
            ]);
        });
    }
};
