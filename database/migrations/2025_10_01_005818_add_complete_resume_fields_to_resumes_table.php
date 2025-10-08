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
            // Personal Information
            $table->string('full_name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('full_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('location')->nullable()->after('phone'); // City, State
            $table->string('linkedin_url')->nullable()->after('location');
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
            $table->string('github_url')->nullable()->after('portfolio_url');

            // Professional Details
            $table->string('headline')->nullable()->after('title'); // Professional headline/tagline
            $table->json('languages')->nullable()->after('certifications'); // Spoken languages
            $table->json('awards')->nullable()->after('languages');
            $table->json('publications')->nullable()->after('awards');
            $table->json('volunteer_work')->nullable()->after('publications');
            $table->json('references')->nullable()->after('volunteer_work');

            // Additional Sections
            $table->text('objective')->nullable()->after('summary'); // Career objective
            $table->json('interests')->nullable()->after('references'); // Personal interests/hobbies
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'email',
                'phone',
                'location',
                'linkedin_url',
                'portfolio_url',
                'github_url',
                'headline',
                'languages',
                'awards',
                'publications',
                'volunteer_work',
                'references',
                'objective',
                'interests',
            ]);
        });
    }
};
