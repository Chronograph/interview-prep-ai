<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenAI\Client as OpenAIClient;

class JobPostingController extends Controller
{
    use AuthorizesRequests;
    protected $openai;

    public function __construct()
    {
        $this->openai = \OpenAI::client(config('services.openai.api_key'));
    }

    /**
     * Display a listing of the user's job postings
     */
    public function index(Request $request): JsonResponse
    {
        $jobPostings = Auth::user()->jobPostings()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($jobPostings);
    }

    /**
     * Store a newly created job posting
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'employment_type' => 'nullable|in:full_time,part_time,contract,internship',
            'remote_option' => 'nullable|boolean',
            'url' => 'nullable|url|max:500',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120' // 5MB max
        ]);

        try {
            // Handle file upload if provided
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('job-postings', $fileName, 'private');

// Extract text from file if it's a text-based format
                if (in_array($file->getClientOriginalExtension(), ['txt'])) {
                    $fileContent = file_get_contents($file->getRealPath());
                    if (empty($validated['description'])) {
                        $validated['description'] = $fileContent;
                    }
                }
            }

            // Parse job posting with AI to extract structured data
            $parsedData = $this->parseJobPosting($validated['description']);

            $jobPosting = JobPosting::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'company' => $validated['company'],
                'description' => $validated['description'],
                'requirements' => $validated['requirements'] ?? $parsedData['requirements'] ?? '',
                'location' => $validated['location'] ?? $parsedData['location'] ?? '',
                'salary_range' => $validated['salary_range'] ?? $parsedData['salary_range'] ?? '',
                'employment_type' => $validated['employment_type'] ?? $parsedData['employment_type'] ?? 'full_time',
                'remote_option' => $validated['remote_option'] ?? $parsedData['remote_option'] ?? false,
                'url' => $validated['url'] ?? '',
                'file_path' => $filePath,
                'skills_required' => $parsedData['skills'] ?? [],
                'experience_level' => $parsedData['experience_level'] ?? 'mid_level',
                'parsed_data' => $parsedData
            ]);

            return response()->json([
                'message' => 'Job posting created successfully',
                'job_posting' => $jobPosting
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create job posting', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to create job posting',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified job posting
     */
    public function show(JobPosting $jobPosting): JsonResponse
    {
        $this->authorize('view', $jobPosting);

        return response()->json($jobPosting);
    }

    /**
     * Update the specified job posting
     */
    public function update(Request $request, JobPosting $jobPosting): JsonResponse
    {
        $this->authorize('update', $jobPosting);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'company' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'employment_type' => 'nullable|in:full_time,part_time,contract,internship',
            'remote_option' => 'nullable|boolean',
            'url' => 'nullable|url|max:500'
        ]);

        try {
            // Re-parse if description changed
            if (isset($validated['description']) && $validated['description'] !== $jobPosting->description) {
                $parsedData = $this->parseJobPosting($validated['description']);
                $validated['parsed_data'] = $parsedData;

// Update fields with parsed data if not explicitly provided
                if (!isset($validated['requirements'])) {
                    $validated['requirements'] = $parsedData['requirements'] ?? $jobPosting->requirements;
                }
                if (!isset($validated['location'])) {
                    $validated['location'] = $parsedData['location'] ?? $jobPosting->location;
                }
            }

            $jobPosting->update($validated);

            return response()->json([
                'message' => 'Job posting updated successfully',
                'job_posting' => $jobPosting->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update job posting', [
                'job_posting_id' => $jobPosting->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to update job posting',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified job posting
     */
    public function destroy(JobPosting $jobPosting): JsonResponse
    {
        $this->authorize('delete', $jobPosting);

        try {
            // Delete associated file if exists
            if ($jobPosting->file_path) {
                Storage::disk('private')->delete($jobPosting->file_path);
            }

            $jobPosting->delete();

            return response()->json([
                'message' => 'Job posting deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete job posting', [
                'job_posting_id' => $jobPosting->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to delete job posting',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse job posting description using AI to extract structured data
     */
    private function parseJobPosting(string $description): array
    {
        try {
            $prompt = "
                Parse the following job posting and extract structured information.
                Return a JSON object with the following fields:
                - requirements: string (key requirements/qualifications)
                - location: string (job location, null if remote/not specified)
                - salary_range: string (salary information if mentioned)
                - employment_type: string (full_time, part_time, contract, or internship)
                - remote_option: boolean (true if remote work is mentioned)
                - skills: array of strings (technical skills mentioned)
                - experience_level: string (entry_level, mid_level, senior_level, or executive)

Job Posting:
                {$description}

Respond with valid JSON only:
            ";

            $response = $this->openai->chat()->create([
                'model' => config('interview.ai_model', 'gpt-4'),
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a job posting parser. Extract structured data and respond with valid JSON only.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.1
            ]);

            $content = $response->choices[0]->message->content;
            $parsedData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse AI response as JSON', [
                    'content' => $content,
                    'error' => json_last_error_msg()
                ]);
                return $this->getDefaultParsedData();
            }

            return array_merge($this->getDefaultParsedData(), $parsedData);

        } catch (\Exception $e) {
            Log::error('Failed to parse job posting with AI', [
                'error' => $e->getMessage()
            ]);

return $this->getDefaultParsedData();
        }
    }

    /**
     * Get default parsed data structure
     */
    private function getDefaultParsedData(): array
    {
        return [
            'requirements' => '',
            'location' => '',
            'salary_range' => '',
            'employment_type' => 'full_time',
            'remote_option' => false,
            'skills' => [],
            'experience_level' => 'mid_level'
        ];
    }

    /**
     * Download job posting file
     */
    public function downloadFile(JobPosting $jobPosting): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorize('view', $jobPosting);

        if (!$jobPosting->file_path || !Storage::disk('private')->exists($jobPosting->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('private')->download(
            $jobPosting->file_path,
            $jobPosting->title . '_job_posting.' . pathinfo($jobPosting->file_path, PATHINFO_EXTENSION)
        );
    }
}
