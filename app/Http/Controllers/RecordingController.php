<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Recording;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class RecordingController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Start a new recording session
     */
    public function start(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'interview_id' => 'required|exists:interviews,id',
            'type' => 'required|in:video,audio',
            'quality' => 'nullable|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $interview = Interview::findOrFail($request->interview_id);
        $this->authorize('update', $interview);

        // Check if there's already an active recording
        $activeRecording = Recording::where('interview_id', $interview->id)
            ->where('status', 'recording')
            ->first();

        if ($activeRecording) {
            return response()->json([
                'success' => false,
                'message' => 'Recording already in progress for this interview'
            ], 409);
        }

        $recording = Recording::create([
            'interview_id' => $interview->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quality' => $request->quality ?? 'medium',
            'status' => 'recording',
            'started_at' => now(),
            'session_id' => Str::uuid(),
        ]);

        return response()->json([
            'success' => true,
            'recording' => $recording,
            'session_id' => $recording->session_id,
            'upload_url' => route('recordings.upload', $recording->id)
        ]);
    }

    /**
     * Stop the current recording
     */
    public function stop(Recording $recording): JsonResponse
    {
        $this->authorize('update', $recording->interview);

        if ($recording->status !== 'recording') {
            return response()->json([
                'success' => false,
                'message' => 'Recording is not currently active'
            ], 400);
        }

        $recording->update([
            'status' => 'processing',
            'ended_at' => now(),
            'duration' => now()->diffInSeconds($recording->started_at)
        ]);

        return response()->json([
            'success' => true,
            'recording' => $recording->fresh()
        ]);
    }

    /**
     * Upload recorded chunks
     */
    public function upload(Request $request, Recording $recording): JsonResponse
    {
        $this->authorize('update', $recording->interview);

        $validator = Validator::make($request->all(), [
            'chunk' => 'required|file|max:10240', // 10MB max per chunk
            'chunk_index' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $chunkIndex = $request->chunk_index;
        $totalChunks = $request->total_chunks;
        $file = $request->file('chunk');

        // Store chunk temporarily
        $chunkPath = "recordings/temp/{$recording->session_id}/chunk_{$chunkIndex}";
        Storage::disk('local')->put($chunkPath, $file->getContent());

        // Check if all chunks are uploaded
        $uploadedChunks = [];
        for ($i = 0; $i < $totalChunks; $i++) {
            $path = "recordings/temp/{$recording->session_id}/chunk_{$i}";
            if (Storage::disk('local')->exists($path)) {
                $uploadedChunks[] = $i;
            }
        }

        if (count($uploadedChunks) === $totalChunks) {
            // All chunks uploaded, merge them
            $this->mergeChunks($recording, $totalChunks);
        }

        return response()->json([
            'success' => true,
            'uploaded_chunks' => count($uploadedChunks),
            'total_chunks' => $totalChunks,
            'complete' => count($uploadedChunks) === $totalChunks
        ]);
    }

    /**
     * Get recording details
     */
    public function show(Recording $recording): JsonResponse
    {
        $this->authorize('view', $recording->interview);

        return response()->json([
            'success' => true,
            'recording' => $recording->load('interview')
        ]);
    }

    /**
     * Get all recordings for an interview
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'interview_id' => 'required|exists:interviews,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $interview = Interview::findOrFail($request->interview_id);
        $this->authorize('view', $interview);

        $recordings = Recording::where('interview_id', $interview->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'recordings' => $recordings
        ]);
    }

    /**
     * Delete a recording
     */
    public function destroy(Recording $recording): JsonResponse
    {
        $this->authorize('delete', $recording->interview);

        // Delete file from storage
        if ($recording->file_path && Storage::disk('public')->exists($recording->file_path)) {
            Storage::disk('public')->delete($recording->file_path);
        }

        $recording->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recording deleted successfully'
        ]);
    }

    /**
     * Stream/download recording file
     */
    public function stream(Recording $recording): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorize('view', $recording->interview);

        if (!$recording->file_path || !Storage::disk('public')->exists($recording->file_path)) {
            abort(404, 'Recording file not found');
        }

        return Storage::disk('public')->response($recording->file_path);
    }

    /**
     * Merge uploaded chunks into final recording file
     */
    private function mergeChunks(Recording $recording, int $totalChunks): void
    {
        $extension = $recording->type === 'video' ? 'webm' : 'wav';
        $filename = "recording_{$recording->id}_{$recording->session_id}.{$extension}";
        $finalPath = "recordings/{$filename}";

        $mergedContent = '';
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = "recordings/temp/{$recording->session_id}/chunk_{$i}";
            if (Storage::disk('local')->exists($chunkPath)) {
                $mergedContent .= Storage::disk('local')->get($chunkPath);
                Storage::disk('local')->delete($chunkPath);
            }
        }

        // Store final file
        Storage::disk('public')->put($finalPath, $mergedContent);

        // Update recording with file info
        $recording->update([
            'file_path' => $finalPath,
            'file_size' => strlen($mergedContent),
            'status' => 'completed'
        ]);

        // Clean up temp directory
         Storage::disk('local')->deleteDirectory("recordings/temp/{$recording->session_id}");
     }
}
