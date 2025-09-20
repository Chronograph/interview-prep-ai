<?php

use App\Http\Controllers\InterviewController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Interview routes
    Route::apiResource('interviews', InterviewController::class);
    Route::post('interviews/{interview}/start', [InterviewController::class, 'start']);
    Route::post('interviews/{interview}/complete', [InterviewController::class, 'complete']);
    Route::get('interviews/{interview}/next-question', [InterviewController::class, 'nextQuestion']);
    Route::post('interviews/{interview}/submit-response', [InterviewController::class, 'submitResponse']);
    Route::post('interviews/{interview}/generate-feedback', [InterviewController::class, 'generateFeedback']);
    Route::get('interviews/statistics/overview', [InterviewController::class, 'statistics']);
    
    // Job Posting routes
    Route::apiResource('job-postings', JobPostingController::class);
    Route::get('job-postings/{jobPosting}/download', [JobPostingController::class, 'downloadFile']);
    
    // Resume routes
    Route::apiResource('resumes', ResumeController::class);
    Route::post('resumes/{resume}/set-primary', [ResumeController::class, 'setPrimary']);
    Route::get('resumes/{resume}/download', [ResumeController::class, 'downloadFile']);
    
    // Recording routes
    Route::post('recordings/start', [RecordingController::class, 'startRecording']);
    Route::post('recordings/{recording}/stop', [RecordingController::class, 'stopRecording']);
    Route::post('recordings/{recording}/upload-chunk', [RecordingController::class, 'uploadChunk']);
    Route::get('recordings', [RecordingController::class, 'getUserRecordings']);
    Route::get('recordings/{recording}', [RecordingController::class, 'getRecording']);
    Route::delete('recordings/{recording}', [RecordingController::class, 'deleteRecording']);
    Route::get('recordings/{recording}/stream', [RecordingController::class, 'streamRecording']);
    
    // Dashboard routes
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', function () {
        $user = auth()->user();
        
        return response()->json([
            'total_interviews' => $user->interviews()->count(),
            'completed_interviews' => $user->interviews()->where('status', 'completed')->count(),
            'average_score' => $user->responses()->avg('ai_score') ?? 0,
            'improvement_trend' => 5.2 // Calculate based on recent performance
        ]);
    });
    
    Route::get('/dashboard/recent-interviews', function () {
        $user = auth()->user();
        
        $recentInterviews = $user->interviews()
            ->with(['jobPosting:id,title,company'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'job_title' => $interview->jobPosting->title ?? 'General Interview',
                    'company' => $interview->jobPosting->company ?? 'N/A',
                    'status' => $interview->status,
                    'score' => $interview->responses()->avg('ai_score'),
                    'created_at' => $interview->created_at->format('M j, Y')
                ];
            });
            
        return response()->json($recentInterviews);
    });
    Route::get('dashboard/progress', [DashboardController::class, 'progress']);
    Route::get('dashboard/analytics', [DashboardController::class, 'analytics']);
});

// Resume routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/resumes', [ResumeController::class, 'index']);
    Route::post('/resumes', [ResumeController::class, 'store']);
    Route::get('/resumes/{resume}', [ResumeController::class, 'show']);
    Route::put('/resumes/{resume}', [ResumeController::class, 'update']);
    Route::delete('/resumes/{resume}', [ResumeController::class, 'destroy']);
    Route::post('/resumes/{resume}/set-primary', [ResumeController::class, 'setPrimary']);
    Route::get('/resumes/{resume}/download', [ResumeController::class, 'download']);
    Route::post('/resumes/parse', [ResumeController::class, 'parseResume']);
});