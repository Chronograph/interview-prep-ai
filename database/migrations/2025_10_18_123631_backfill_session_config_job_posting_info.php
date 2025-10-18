<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\InterviewSession;
use App\Models\JobPosting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill session_config with job posting information for existing sessions
        $sessions = InterviewSession::whereNotNull('job_posting_id')
            ->where('is_practice', true)
            ->get();

        foreach ($sessions as $session) {
            $jobPosting = JobPosting::find($session->job_posting_id);
            if ($jobPosting) {
                $sessionConfig = $session->session_config ?? [];
                $sessionConfig['job_posting'] = [
                    'id' => $jobPosting->id,
                    'company' => $jobPosting->company,
                    'title' => $jobPosting->title,
                    'description' => $jobPosting->description,
                ];
                
                $session->update(['session_config' => $sessionConfig]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove job posting info from session_config
        $sessions = InterviewSession::whereNotNull('session_config')
            ->where('is_practice', true)
            ->get();

        foreach ($sessions as $session) {
            $sessionConfig = $session->session_config ?? [];
            unset($sessionConfig['job_posting']);
            $session->update(['session_config' => $sessionConfig]);
        }
    }
};