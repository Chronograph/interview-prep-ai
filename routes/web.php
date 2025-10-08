<?php

use App\Livewire\Dashboard;
use App\Livewire\ResumeManager;
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
    Route::get('/profile', function () {
        return view('profile.livewire');
    })->name('profile.edit');
    Route::get('/profile/settings', function () {
        return response('Profile settings interface: TODO convert to Livewire ProfileSettingsManager component in app/Livewire/ and register as route.');
    })->name('profile.settings');
    Route::patch('/profile', function () {
        return response('Profile update handled via Livewire components');
    })->name('profile.update');
    Route::delete('/profile', function () {
        return response('Profile deletion handled via Livewire component');
    })->name('profile.destroy');

    // Analytics routes (Livewire)
    Route::get('/analytics', function () {
        return view('analytics.livewire');
    })->name('analytics.dashboard');

    // Application tracking routes (Livewire)
    Route::get('/analytics/applications', function () {
        return view('analytics.applications.livewire');
    })->name('analytics.applications.index');

    // Interview Practice routes
    Route::get('/practice/sessions', \App\Livewire\PracticeSessions::class)->name('practice.sessions');
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
    Route::get('/interview-sessions/create', function () {
        return view('interview-sessions.livewire');
    })->name('interview-sessions.create');
    // Interview Session routes (Livewire)
    Route::get('/interview-sessions/{session}', function ($session) {
        return response('Session details: TODO convert to Livewire session detail component for interview sessions.');
    })->name('interview-sessions.show');

    // Cheat Sheet routes (Livewire)
    Route::get('/cheat-sheets', function () {
        return view('cheat-sheets.livewire');
    })->name('cheat-sheets.index');

    // AI Persona routes (Livewire)
    Route::get('/ai-personas', function () {
        return view('ai-personas.livewire');
    })->name('ai-personas.index');

    // Company Briefs routes (Livewire)
    Route::get('/company-briefs', function () {
        return view('company-briefs.livewire');
    })->name('company-briefs.index');

    // Resume routes (Livewire)
    Route::get('/resumes', ResumeManager::class)->name('resumes.index');
});

require __DIR__.'/auth.php';
