<?php

use App\Http\Controllers\CheckoutController;
use App\Livewire\AiPersonaManager;
use App\Livewire\Analytics;
use App\Livewire\ApplicationManager;
use App\Livewire\BillingManager;
use App\Livewire\CheatSheetManager;
use App\Livewire\CompanyBriefManager;
use App\Livewire\Dashboard;
use App\Livewire\InterviewSessionManager;
use App\Livewire\PracticeSessions;
use App\Livewire\ProfileManager;
use App\Livewire\ResumeManager;
use App\Livewire\TeamManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/upcoming-interviews', \App\Livewire\UpcomingInterviews::class)->name('upcoming-interviews');
    // Profile routes (Livewire)
    Route::get('/profile', ProfileManager::class)->name('profile.edit');

    // Analytics routes (Livewire)
    Route::get('/analytics', Analytics::class)->name('analytics.dashboard');

    // Application tracking routes (Livewire)
    Route::get('/analytics/applications', ApplicationManager::class)->name('analytics.applications.index');

    // Interview Practice routes
    Route::get('/practice/sessions', PracticeSessions::class)->name('practice.sessions');
    Route::get('/practice/mock-interviews', function () {
        return view('practice.mock-interviews');
    })->name('practice.mock-interviews');

    Route::get('/practice/skills-assessment', function () {
        return view('practice.skills-assessment');
    })->name('practice.skills-assessment');

    Route::get('/practice/video-practice', function () {
        return view('practice.video-practice');
    })->name('practice.video-practice');

    // Interview Session routes (Livewire)
    Route::get('/interview-sessions/create', InterviewSessionManager::class)->name('interview-sessions.create');
    // Interview Session routes (Livewire)
    Route::get('/interview-sessions/{session}', function ($session) {
        return response('Session details: TODO convert to Livewire session detail component for interview sessions.');
    })->name('interview-sessions.show');

    // Cheat Sheet routes (Livewire)
    Route::get('/cheat-sheets', CheatSheetManager::class)->name('cheat-sheets.index');

    // AI Persona routes (Livewire)
    Route::get('/ai-personas', AiPersonaManager::class)->name('ai-personas.index');

    // Company Briefs routes (Livewire)
    Route::get('/company-briefs', CompanyBriefManager::class)->name('company-briefs.index');

    // Resume routes (Livewire)
    Route::get('/resumes', ResumeManager::class)->name('resumes.index');

    // Team routes (Livewire)
    Route::get('/teams', TeamManager::class)->name('teams.index');

    // Billing routes
    Route::get('/billing', BillingManager::class)->name('billing.index');
    Route::get('/billing/checkout/{plan}', [CheckoutController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/success', [CheckoutController::class, 'success'])->name('billing.success');
});

// Stripe Webhook route (outside auth middleware)
Route::post('/stripe/webhook', [\App\Http\Controllers\WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

require __DIR__.'/auth.php';
