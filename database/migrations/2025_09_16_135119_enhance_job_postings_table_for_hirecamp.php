<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // Application tracking
            $table->string('application_status')->default('not_applied')->after('is_active');
            // not_applied, applied, phone_screen, interview_scheduled, interviewed, offer, rejected, withdrawn
            $table->date('application_date')->nullable()->after('application_status');
            $table->date('response_deadline')->nullable()->after('application_date');
            $table->text('application_notes')->nullable()->after('response_deadline');

            // Interview tracking
            $table->boolean('got_interview')->default(false)->after('application_notes');
            $table->datetime('interview_scheduled_at')->nullable()->after('got_interview');
            $table->text('interview_feedback')->nullable()->after('interview_scheduled_at');
            $table->json('interview_questions_asked')->nullable()->after('interview_feedback'); // Questions they actually asked

            // Offer tracking
            $table->boolean('got_offer')->default(false)->after('interview_questions_asked');
            $table->decimal('offer_salary', 10, 2)->nullable()->after('got_offer');
            $table->json('offer_benefits')->nullable()->after('offer_salary');
            $table->date('offer_deadline')->nullable()->after('offer_benefits');
            $table->string('offer_status')->nullable()->after('offer_deadline'); // pending, accepted, declined, negotiating

            // Company rating and feedback
            $table->integer('company_rating')->nullable()->after('offer_status'); // 1-5 stars
            $table->text('company_review')->nullable()->after('company_rating');
            $table->boolean('would_interview_again')->nullable()->after('company_review');

            // Job posting metadata
            $table->date('posting_date')->nullable()->after('would_interview_again');
            $table->date('application_deadline')->nullable()->after('posting_date');
            $table->string('job_board')->nullable()->after('application_deadline'); // LinkedIn, Indeed, etc.
            $table->text('recruiter_contact')->nullable()->after('job_board');
            $table->json('benefits')->nullable()->after('recruiter_contact');
            $table->boolean('remote_work')->default(false)->after('benefits');
            $table->string('visa_sponsorship')->nullable()->after('remote_work'); // yes, no, maybe

            // AI-generated content
            $table->json('ai_match_score')->nullable()->after('visa_sponsorship'); // How well it matches user profile
            $table->json('preparation_suggestions')->nullable()->after('ai_match_score'); // AI suggestions for prep
            $table->boolean('is_favorite')->default(false)->after('preparation_suggestions');

            // Tracking
            $table->timestamp('last_viewed_at')->nullable()->after('is_favorite');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn([
                'application_status', 'application_date', 'response_deadline', 'application_notes',
                'got_interview', 'interview_scheduled_at', 'interview_feedback', 'interview_questions_asked',
                'got_offer', 'offer_salary', 'offer_benefits', 'offer_deadline', 'offer_status',
                'company_rating', 'company_review', 'would_interview_again', 'posting_date',
                'application_deadline', 'job_board', 'recruiter_contact', 'benefits', 'remote_work',
                'visa_sponsorship', 'ai_match_score', 'preparation_suggestions', 'is_favorite',
                'last_viewed_at',
            ]);
        });
    }
};
