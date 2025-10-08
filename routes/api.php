<?php

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

// Simple test route without authentication
Route::get('health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

// Test LM Studio connection without authentication
Route::post('test/lm-studio', function (Request $request) {
    try {
        $aiService = app(App\Services\AIService::class);
        $response = $aiService->generateResponse($request->input('message', 'Hello, AI!'));

        return response()->json([
            'success' => true,
            'message' => 'LM Studio connection successful',
            'data' => [
                'input' => $request->input('message', 'Hello, AI!'),
                'response' => $response,
                'provider' => config('prism.default_provider', 'lmstudio'),
                'model' => config('prism.providers.lmstudio.model', 'local-model'),
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'LM Studio connection failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
