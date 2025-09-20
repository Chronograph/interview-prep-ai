<?php

namespace App\Http\Controllers;

use App\Models\UserDocument;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserDocumentController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display a listing of the user's documents.
     */
    public function index(Request $request): JsonResponse
    {
        $documents = $request->user()->userDocuments()
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($documents);
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB max
            'type' => 'required|in:resume,cover_letter,portfolio,transcript,certificate',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('documents/' . $request->user()->id, $filename, 'private');

            $document = UserDocument::create([
                'user_id' => $request->user()->id,
                'type' => $request->type,
                'title' => $request->title ?: $file->getClientOriginalName(),
                'description' => $request->description,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            // If it's a resume, analyze it with AI
            if ($request->type === 'resume') {
                $this->analyzeResume($document);
            }

            return response()->json([
                'message' => 'Document uploaded successfully',
                'document' => $document
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified document.
     */
    public function show(UserDocument $document): JsonResponse
    {
        $this->authorize('view', $document);

        return response()->json($document);
    }

    /**
     * Update the specified document.
     */
    public function update(Request $request, UserDocument $document): JsonResponse
    {
        $this->authorize('update', $document);

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_primary' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If setting as primary, unset other primary documents of the same type
        if ($request->is_primary && $request->is_primary == true) {
            UserDocument::where('user_id', $document->user_id)
                ->where('type', $document->type)
                ->where('id', '!=', $document->id)
                ->update(['is_primary' => false]);
        }

        $document->update($request->only(['title', 'description', 'is_primary']));

        return response()->json([
            'message' => 'Document updated successfully',
            'document' => $document
        ]);
    }

    /**
     * Remove the specified document.
     */
    public function destroy(UserDocument $document): JsonResponse
    {
        $this->authorize('delete', $document);

        try {
            // Delete the file from storage
            if (Storage::disk('private')->exists($document->file_path)) {
                Storage::disk('private')->delete($document->file_path);
            }

            $document->delete();

            return response()->json([
                'message' => 'Document deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download the specified document.
     */
    public function download(UserDocument $document)
    {
        $this->authorize('view', $document);

        if (!Storage::disk('private')->exists($document->file_path)) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        return Storage::disk('private')->download(
            $document->file_path,
            $document->file_name
        );
    }

    /**
     * Get AI analysis of a resume.
     */
    public function getResumeAnalysis(UserDocument $document): JsonResponse
    {
        $this->authorize('view', $document);

        if ($document->type !== 'resume') {
            return response()->json([
                'message' => 'Document is not a resume'
            ], 400);
        }

        if (!$document->ai_analysis) {
            // Trigger analysis if not done yet
            $this->analyzeResume($document);
            $document->refresh();
        }

        return response()->json([
            'analysis' => $document->ai_analysis
        ]);
    }

    /**
     * Re-analyze a resume with AI.
     */
    public function reanalyzeResume(UserDocument $document): JsonResponse
    {
        $this->authorize('update', $document);

        if ($document->type !== 'resume') {
            return response()->json([
                'message' => 'Document is not a resume'
            ], 400);
        }

        try {
            $this->analyzeResume($document);
            $document->refresh();

            return response()->json([
                'message' => 'Resume re-analyzed successfully',
                'analysis' => $document->ai_analysis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to analyze resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get document statistics for the user.
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $stats = [
            'total_documents' => $user->userDocuments()->count(),
            'by_type' => $user->userDocuments()
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'total_size' => $user->userDocuments()->sum('file_size'),
            'primary_resume' => $user->userDocuments()
                ->where('type', 'resume')
                ->where('is_primary', true)
                ->first(),
        ];

        return response()->json($stats);
    }

    /**
     * Analyze a resume with AI.
     */
    protected function analyzeResume(UserDocument $document): void
    {
        try {
            // Extract text from the document (simplified - in production you'd use proper PDF/DOC parsing)
            $fileContent = Storage::disk('private')->get($document->file_path);
            
            // For now, we'll assume text extraction is handled elsewhere
            // In production, you'd use libraries like Smalot\PdfParser or similar
            $extractedText = $this->extractTextFromFile($document);
            
            if ($extractedText) {
                $analysis = $this->aiService->analyzeResume($extractedText);
                
                $document->update([
                    'ai_analysis' => $analysis,
                    'analyzed_at' => now(),
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Resume analysis failed: ' . $e->getMessage());
        }
    }

    /**
     * Extract text from uploaded file.
     * This is a placeholder - implement proper text extraction based on file type.
     */
    protected function extractTextFromFile(UserDocument $document): ?string
    {
        // This is a simplified implementation
        // In production, you'd use proper libraries for PDF/DOC parsing
        
        if ($document->mime_type === 'text/plain') {
            return Storage::disk('private')->get($document->file_path);
        }
        
        // For PDF and DOC files, you'd use appropriate parsers
        // For now, return null to indicate text extraction is not implemented
        return null;
    }
}