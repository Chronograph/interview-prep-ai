<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenAI\Client as OpenAIClient;

class ResumeController extends Controller
{
    use AuthorizesRequests;

    protected $openai;

    public function __construct()
    {
        $this->openai = \OpenAI::client(config('services.openai.api_key'));
    }

    /**
     * Display a listing of the user's resumes
     */
    public function index(Request $request): JsonResponse
    {
        $resumes = Auth::user()->resumes()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($resumes);
    }

    /**
     * Store a newly created resume
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB max
            'is_primary' => 'nullable|boolean'
        ]);

        try {
            // Handle file upload
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('resumes', $fileName, 'private');

            // Extract text content from file
            $extractedContent = $this->extractTextFromFile($file);
            $content = $validated['content'] ?? $extractedContent;

            // Parse resume with AI to extract structured data
            $parsedData = $this->parseResume($content);

            // Handle primary resume logic
            if ($validated['is_primary'] ?? false) {
                // Set all other resumes as non-primary
                Auth::user()->resumes()->update(['is_primary' => false]);
            }

            $resume = Resume::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'content' => $content,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'is_primary' => $validated['is_primary'] ?? false,
                'skills' => $parsedData['skills'] ?? [],
                'experience_years' => $parsedData['experience_years'] ?? 0,
                'education' => $parsedData['education'] ?? [],
                'work_experience' => $parsedData['work_experience'] ?? [],
                'parsed_data' => $parsedData
            ]);

            return response()->json([
                'message' => 'Resume uploaded successfully',
                'resume' => $resume
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to upload resume', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to upload resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resume
     */
    public function show(Resume $resume): JsonResponse
    {
        $this->authorize('view', $resume);

        return response()->json($resume);
    }

    /**
     * Update the specified resume
     */
    public function update(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'is_primary' => 'nullable|boolean'
        ]);

        try {
            // Handle primary resume logic
            if (isset($validated['is_primary']) && $validated['is_primary']) {
                // Set all other resumes as non-primary
                Auth::user()->resumes()->where('id', '!=', $resume->id)->update(['is_primary' => false]);
            }

            // Re-parse if content changed
            if (isset($validated['content']) && $validated['content'] !== $resume->content) {
                $parsedData = $this->parseResume($validated['content']);
                $validated['parsed_data'] = $parsedData;
                $validated['skills'] = $parsedData['skills'] ?? [];
                $validated['experience_years'] = $parsedData['experience_years'] ?? 0;
                $validated['education'] = $parsedData['education'] ?? [];
                $validated['work_experience'] = $parsedData['work_experience'] ?? [];
            }

            $resume->update($validated);

            return response()->json([
                'message' => 'Resume updated successfully',
                'resume' => $resume->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update resume', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to update resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resume
     */
    public function destroy(Resume $resume): JsonResponse
    {
        $this->authorize('delete', $resume);

        try {
            // Delete associated file
            if ($resume->file_path) {
                Storage::disk('private')->delete($resume->file_path);
            }

            $resume->delete();

            return response()->json([
                'message' => 'Resume deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete resume', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to delete resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set a resume as primary
     */
    public function setPrimary(Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);

        try {
            // Set all other resumes as non-primary
            Auth::user()->resumes()->update(['is_primary' => false]);
            
            // Set this resume as primary
            $resume->update(['is_primary' => true]);

            return response()->json([
                'message' => 'Resume set as primary successfully',
                'resume' => $resume->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to set primary resume', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to set primary resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download resume file
     */
    public function downloadFile(Resume $resume): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorize('view', $resume);

        if (!$resume->file_path || !Storage::disk('private')->exists($resume->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('private')->download(
            $resume->file_path,
            $resume->file_name ?? 'resume.' . pathinfo($resume->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Extract text content from uploaded file
     */
    private function extractTextFromFile($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        try {
            switch ($extension) {
                case 'txt':
                    return file_get_contents($file->getRealPath());
                
                case 'pdf':
                    // For PDF parsing, you might want to use a library like smalot/pdfparser
                    // For now, return empty string and let user provide content manually
                    return '';
                
                case 'doc':
                case 'docx':
                    // For Word document parsing, you might want to use phpoffice/phpword
                    // For now, return empty string and let user provide content manually
                    return '';
                
                default:
                    return '';
            }
        } catch (\Exception $e) {
            Log::warning('Failed to extract text from file', [
                'file_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }

    /**
     * Parse resume content using AI to extract structured data
     */
    private function parseResume(string $content): array
    {
        if (empty(trim($content))) {
            return $this->getDefaultResumeData();
        }

        try {
            $prompt = "
                Parse the following resume and extract structured information.
                Return a JSON object with the following fields:
                - skills: array of strings (technical and soft skills)
                - experience_years: integer (total years of professional experience)
                - education: array of objects with fields: degree, institution, year, field_of_study
                - work_experience: array of objects with fields: title, company, duration, description
                - certifications: array of strings (professional certifications)
                - languages: array of strings (spoken languages)
                - summary: string (brief professional summary)
                
                Resume Content:
                {$content}
                
                Respond with valid JSON only:
            ";

            $response = $this->openai->chat()->create([
                'model' => config('interview.ai_model', 'gpt-4'),
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a resume parser. Extract structured data and respond with valid JSON only.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 1500,
                'temperature' => 0.1
            ]);

            $responseContent = $response->choices[0]->message->content;
            $parsedData = json_decode($responseContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse AI response as JSON', [
                    'content' => $responseContent,
                    'error' => json_last_error_msg()
                ]);
                return $this->getDefaultResumeData();
            }

            return array_merge($this->getDefaultResumeData(), $parsedData);

        } catch (\Exception $e) {
            Log::error('Failed to parse resume with AI', [
                'error' => $e->getMessage()
            ]);
            
            return $this->getDefaultResumeData();
        }
    }

    /**
     * Get default resume data structure
     */
    private function getDefaultResumeData(): array
    {
        return [
            'skills' => [],
            'experience_years' => 0,
            'education' => [],
            'work_experience' => [],
            'certifications' => [],
            'languages' => [],
            'summary' => ''
        ];
    }
}
