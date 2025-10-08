<?php

namespace App\Services;

use App\Models\CheatSheet;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CheatSheetService
{
    public function __construct(
        private readonly AIService $aiService
    ) {}

    public function generateGeneralCheatSheet(
        User $user,
        string $topic,
        string $category,
        ?string $context = null
    ): CheatSheet {
        return $this->generateCheatSheet($user, $topic, $category, $context ?: "General cheat sheet for: {$topic}");
    }

    public function generatePersonalizedCheatSheet(
        User $user,
        string $topic,
        string $category,
        ?string $context = null
    ): CheatSheet {
        $userProfile = $this->buildUserProfile($user);
        $context = $context ? "Personalized for {$user->name}: {$context}" : "Personalized cheat sheet for: {$topic}";

        return $this->generateCheatSheet($user, $topic, $category, $context);
    }

    public function generateJobSpecificCheatSheet(
        User $user,
        JobPosting $jobPosting,
        string $topic,
        string $category,
        ?string $context = null
    ): CheatSheet {
        $context = $context ? "Job-specific for {$jobPosting->company_name} - {$jobPosting->title}: {$context}" : "Job-specific cheat sheet for {$jobPosting->company_name}: {$topic}";

        return $this->generateCheatSheet($user, $topic, $category, $context, $jobPosting);
    }

    public function refreshCheatSheet(CheatSheet $cheatSheet): CheatSheet
    {
        try {
            $user = $cheatSheet->user;
            $jobPosting = $cheatSheet->jobPosting;

            // Generate new content
            $newCheatSheet = $this->generateCheatSheet(
                $user,
                $cheatSheet->title,
                $cheatSheet->category,
                $cheatSheet->topic_description,
                $jobPosting
            );

            // Update the existing cheat sheet with new content
            $cheatSheet->update([
                'key_points' => $newCheatSheet->key_points,
                'suggested_response' => $newCheatSheet->suggested_response,
                'examples' => $newCheatSheet->examples,
                'do_say' => $newCheatSheet->do_say,
                'dont_say' => $newCheatSheet->dont_say,
                'follow_up_questions' => $newCheatSheet->follow_up_questions,
            ]);

            // Delete the temporary new cheat sheet
            $newCheatSheet->delete();

            return $cheatSheet->fresh();

        } catch (\Exception $e) {
            Log::error('Failed to refresh cheat sheet', [
                'cheat_sheet_id' => $cheatSheet->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception("Failed to refresh cheat sheet: {$e->getMessage()}");
        }
    }

    public function getRecommendations(User $user): array
    {
        return $this->getRecommendedCheatSheets($user, 5);
    }

    public function generateCheatSheet(
        User $user,
        string $title,
        string $category,
        string $topicDescription,
        ?JobPosting $jobPosting = null
    ): CheatSheet {
        try {
            // Build user profile context
            $userProfile = $this->buildUserProfile($user);

            // Build job context if provided
            $jobContext = $jobPosting ? $this->buildJobContext($jobPosting) : [];

            // Generate cheat sheet content using AI
            $content = $this->aiService->generateCheatSheet(
                $topicDescription,
                $userProfile,
                $jobContext
            );

            // Create cheat sheet
            $cheatSheet = CheatSheet::create([
                'user_id' => $user->id,
                'job_posting_id' => $jobPosting?->id,
                'title' => $title,
                'category' => $category,
                'topic_description' => $topicDescription,
                'key_points' => $content['key_points'] ?? [],
                'suggested_response' => $content['suggested_response_framework'] ?? '',
                'examples' => $content['examples'] ?? [],
                'do_say' => $content['dos'] ?? [],
                'dont_say' => $content['donts'] ?? [],
                'follow_up_questions' => $content['follow_up_questions'] ?? [],
                'usage_count' => 0,
                'average_score' => null,
                'last_practiced_at' => null,
                'is_custom' => false,
            ]);

            return $cheatSheet;

        } catch (\Exception $e) {
            Log::error('Failed to generate cheat sheet', [
                'user_id' => $user->id,
                'title' => $title,
                'category' => $category,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception("Failed to generate cheat sheet: {$e->getMessage()}");
        }
    }

    public function generatePersonalizedCheatSheets(User $user): array
    {
        $cheatSheets = [];

        // Common interview topics
        $topics = [
            [
                'title' => 'Tell Me About Yourself',
                'category' => 'introduction',
                'description' => 'Personal introduction and professional summary',
            ],
            [
                'title' => 'Why Do You Want This Job?',
                'category' => 'motivation',
                'description' => 'Expressing genuine interest and alignment with role',
            ],
            [
                'title' => 'Strengths and Weaknesses',
                'category' => 'self_assessment',
                'description' => 'Honest self-evaluation with growth mindset',
            ],
            [
                'title' => 'Behavioral Questions (STAR Method)',
                'category' => 'behavioral',
                'description' => 'Structured approach to behavioral interview questions',
            ],
            [
                'title' => 'Questions to Ask the Interviewer',
                'category' => 'questions',
                'description' => 'Thoughtful questions that show engagement and research',
            ],
        ];

        // Add role-specific topics based on user's target roles
        if ($user->target_roles) {
            foreach ($user->target_roles as $role) {
                $topics[] = [
                    'title' => "Technical Questions for {$role}",
                    'category' => 'technical',
                    'description' => "Technical interview preparation for {$role} positions",
                ];
            }
        }

        foreach ($topics as $topic) {
            try {
                // Check if cheat sheet already exists
                $existing = $user->cheatSheets()
                    ->where('title', $topic['title'])
                    ->first();

                if (! $existing) {
                    $cheatSheet = $this->generateCheatSheet(
                        $user,
                        $topic['title'],
                        $topic['category'],
                        $topic['description']
                    );

                    $cheatSheets[] = $cheatSheet;
                }
            } catch (\Exception $e) {
                Log::warning('Failed to generate personalized cheat sheet', [
                    'user_id' => $user->id,
                    'topic' => $topic['title'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $cheatSheets;
    }

    public function updateCheatSheetFromPractice(
        CheatSheet $cheatSheet,
        float $score,
        array $feedback = []
    ): void {
        try {
            // Update usage and score
            $cheatSheet->incrementUsage();
            $cheatSheet->updateScore($score);

            // If feedback suggests improvements, regenerate content
            if (! empty($feedback['suggestions'])) {
                $this->incorporateFeedback($cheatSheet, $feedback);
            }

        } catch (\Exception $e) {
            Log::error('Failed to update cheat sheet from practice', [
                'cheat_sheet_id' => $cheatSheet->id,
                'score' => $score,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function generateJobSpecificCheatSheets(
        User $user,
        JobPosting $jobPosting
    ): array {
        $cheatSheets = [];

        // Generate company-specific cheat sheets
        $companyTopics = [
            [
                'title' => "Why {$jobPosting->company_name}?",
                'category' => 'company_specific',
                'description' => "Reasons for wanting to work at {$jobPosting->company_name}",
            ],
            [
                'title' => "Questions About {$jobPosting->company_name}",
                'category' => 'company_questions',
                'description' => 'Thoughtful questions about the company and role',
            ],
        ];

        // Add role-specific topics
        if ($jobPosting->title) {
            $companyTopics[] = [
                'title' => "Technical Skills for {$jobPosting->title}",
                'category' => 'role_specific',
                'description' => "Technical competencies required for {$jobPosting->title}",
            ];
        }

        foreach ($companyTopics as $topic) {
            try {
                $cheatSheet = $this->generateCheatSheet(
                    $user,
                    $topic['title'],
                    $topic['category'],
                    $topic['description'],
                    $jobPosting
                );

                $cheatSheets[] = $cheatSheet;

            } catch (\Exception $e) {
                Log::warning('Failed to generate job-specific cheat sheet', [
                    'user_id' => $user->id,
                    'job_posting_id' => $jobPosting->id,
                    'topic' => $topic['title'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $cheatSheets;
    }

    public function getRecommendedCheatSheets(User $user, int $limit = 5): array
    {
        // Get cheat sheets that need practice (low scores or not practiced recently)
        $needsPractice = $user->cheatSheets()
            ->needsPractice()
            ->limit($limit)
            ->get();

        if ($needsPractice->count() >= $limit) {
            return $needsPractice->toArray();
        }

        // Fill remaining slots with popular cheat sheets
        $popular = $user->cheatSheets()
            ->mostPracticed()
            ->whereNotIn('id', $needsPractice->pluck('id'))
            ->limit($limit - $needsPractice->count())
            ->get();

        return $needsPractice->concat($popular)->toArray();
    }

    private function buildUserProfile(User $user): string
    {
        $profile = [];

        if ($user->current_title) {
            $profile[] = "Current Role: {$user->current_title}";
        }

        if ($user->current_company) {
            $profile[] = "Current Company: {$user->current_company}";
        }

        if ($user->years_experience) {
            $profile[] = "Experience: {$user->years_experience} years";
        }

        if ($user->target_roles) {
            $profile[] = 'Target Roles: '.implode(', ', $user->target_roles);
        }

        if ($user->skills) {
            $profile[] = 'Skills: '.implode(', ', array_slice($user->skills, 0, 10));
        }

        // Include resume highlights if available
        $primaryResume = $user->userDocuments()->where('document_type', 'resume')->where('is_primary', true)->first();
        if ($primaryResume && $primaryResume->ai_summary) {
            $profile[] = "Resume Summary: {$primaryResume->ai_summary}";
        }

        return implode('. ', $profile);
    }

    private function buildJobContext(JobPosting $jobPosting): array
    {
        return [
            'company' => $jobPosting->company_name,
            'title' => $jobPosting->title,
            'description' => $jobPosting->description,
            'requirements' => $jobPosting->requirements ?? [],
            'benefits' => $jobPosting->benefits ?? [],
            'location' => $jobPosting->location,
            'salary_range' => $jobPosting->salary_range,
        ];
    }

    private function incorporateFeedback(CheatSheet $cheatSheet, array $feedback): void
    {
        try {
            // Build improvement prompt
            $improvements = implode(', ', $feedback['suggestions'] ?? []);
            $weaknesses = implode(', ', $feedback['weaknesses'] ?? []);

            $prompt = 'Improve this cheat sheet based on feedback:

Current Content: '.json_encode([
                'key_points' => $cheatSheet->key_points,
                'suggested_response' => $cheatSheet->suggested_response,
                'examples' => $cheatSheet->examples,
            ])."

Feedback Suggestions: {$improvements}
Identified Weaknesses: {$weaknesses}

Provide improved content in the same JSON format, addressing the feedback.";

            $response = $this->aiService->generateResponse($prompt, [
                'temperature' => 0.4,
                'max_tokens' => 1200,
            ]);

            $improvedContent = json_decode($response, true);

            if ($improvedContent && is_array($improvedContent)) {
                $cheatSheet->update([
                    'key_points' => $improvedContent['key_points'] ?? $cheatSheet->key_points,
                    'suggested_response' => $improvedContent['suggested_response'] ?? $cheatSheet->suggested_response,
                    'examples' => $improvedContent['examples'] ?? $cheatSheet->examples,
                ]);
            }

        } catch (\Exception $e) {
            Log::warning('Failed to incorporate feedback into cheat sheet', [
                'cheat_sheet_id' => $cheatSheet->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
