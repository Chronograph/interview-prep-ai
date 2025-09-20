<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.dashboard');
    Route::get('/analytics/mastery', [AnalyticsController::class, 'getMasteryData'])->name('analytics.mastery');
    Route::get('/analytics/topics', [AnalyticsController::class, 'getTopicProgressData'])->name('analytics.topics');
    Route::get('/analytics/data/applications', [AnalyticsController::class, 'getApplicationData'])->name('analytics.data.applications');
    
    // Application tracking routes
    Route::resource('analytics/applications', ApplicationController::class, [
        'names' => [
            'index' => 'analytics.applications.index',
            'create' => 'analytics.applications.create',
            'store' => 'analytics.applications.store',
            'show' => 'analytics.applications.show',
            'edit' => 'analytics.applications.edit',
            'update' => 'analytics.applications.update',
            'destroy' => 'analytics.applications.destroy'
        ]
    ]);
    
    // Additional application routes
    Route::patch('/analytics/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('analytics.applications.status');
    Route::patch('/analytics/applications/{application}/favorite', [ApplicationController::class, 'toggleFavorite'])->name('analytics.applications.favorite');
});

require __DIR__.'/auth.php';
