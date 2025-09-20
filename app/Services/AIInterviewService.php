<?php

namespace App\Services;

use App\Models\Interview;
use App\Models\InterviewQuestion;
use App\Models\JobPosting;
use App\Models\Resume;
use OpenAI;
use Illuminate\Support\Facades\Log;

class AIInterviewService
{
    protected $openai;
    protected $maxQuestions;
    protected $questionTypes;

    public function __construct()
    {
        $this->openai = OpenAI::client(config('services.openai.api_key'));
        $this->maxQuestions = config('interview.max_questions', 10);
        $this->questionTypes = [
            'behavioral' => 'Behavioral questions about past experiences and situations',
            'technical' => 'Technical questions related to job requirements and skills',
            'situational' => 'Hypothetical scenarios and problem-solving questions',
            'company_culture' => 'Questions about company fit and cultural alignment'
        ];
    }

    /**
     * Start a new AI-powered interview session
     */
    public function startInterview(Interview $interview): InterviewQuestion
    {
        $interview->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'ai_context' => $this->buildInitialContext($interview)
        ]);

        return $this->generateNextQuestion($interview);
    }

    /**
     * Generate the next question based on interview context
     */
    public function generateNextQuestion(Interview $interview): InterviewQuestion
    {
        $context = $this->buildQuestionContext($interview);
        $questionOrder = $interview->questions()->count() + 1;
        
        if ($questionOrder > $this->maxQuestions) {
            $this->completeInterview($interview);
            throw new \Exception('Interview has reached maximum questions limit');
        }

        $questionType = $this->determineQuestionType($interview, $questionOrder);
        $prompt = $this->buildQuestionPrompt($context, $questionType, $questionOrder);

        try {
            $response = $this->openai->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert interview coach conducting a professional job interview. Generate thoughtful, relevant questions based on the job requirements and candidate background.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7
            ]);

            $questionText = trim($response->choices[0]->message->content);

            return InterviewQuestion::create([
                'interview_id' => $interview->id,
                'question_order' => $questionOrder,
                'question' => $questionText,
                'question_type' => $questionType,
                'asked_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate interview question', [
                'interview_id' => $interview->id,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Failed to generate interview question: ' . $e->getMessage());
        }
    }

    /**
     * Process user's response and generate follow-up if needed
     */
    public function processResponse(InterviewQuestion $question, string $response): array
    {
        $question->update([
            'user_response' => $response,
            'answered_at' => now(),
            'response_time_seconds' => now()->diffInSeconds($question->asked_at)
        ]);

        // Generate AI analysis of the response
        $analysis = $this->analyzeResponse($question, $response);
        $question->update(['analysis' => $analysis]);

        // Determine if follow-up question is needed
        $followUp = $this->generateFollowUpIfNeeded($question, $response, $analysis);
        
        if ($followUp) {
            $question->update(['ai_follow_up' => $followUp]);
        }

        return [
            'analysis' => $analysis,
            'follow_up' => $followUp,
            'needs_clarification' => $analysis['needs_clarification'] ?? false
        ];
    }

    /**
     * Complete the interview and generate overall assessment
     */
    public function completeInterview(Interview $interview): void
    {
        $interview->update([
            'status' => 'completed',
            'completed_at' => now(),
            'overall_score' => $this->calculateOverallScore($interview)
        ]);
    }

    /**
     * Build initial context for the interview
     */
    protected function buildInitialContext(Interview $interview): array
    {
        $jobPosting = $interview->jobPosting;
        $resume = $interview->resume;

        return [
            'job_title' => $jobPosting->title,
            'company' => $jobPosting->company,
            'job_requirements' => $jobPosting->requirements,
            'job_skills' => $jobPosting->skills,
            'candidate_experience' => $resume->experience,
            'candidate_skills' => $resume->skills,
            'interview_type' => $interview->interview_type,
            'duration_minutes' => $interview->duration_minutes
        ];
    }

    /**
     * Build context for generating questions
     */
    protected function buildQuestionContext(Interview $interview): array
    {
        $context = $interview->ai_context;
        $previousQuestions = $interview->questions()->with('feedback')->get();
        
        $context['previous_questions'] = $previousQuestions->map(function ($q) {
            return [
                'question' => $q->question,
                'type' => $q->question_type,
                'response' => $q->user_response,
                'analysis' => $q->analysis
            ];
        })->toArray();

        return $context;
    }

    /**
     * Determine the type of question to ask next
     */
    protected function determineQuestionType(Interview $interview, int $questionOrder): string
    {
        $questionCounts = $interview->questions()
            ->selectRaw('question_type, COUNT(*) as count')
            ->groupBy('question_type')
            ->pluck('count', 'question_type')
            ->toArray();

        // Start with behavioral questions
        if ($questionOrder <= 2) {
            return 'behavioral';
        }
        
        // Mix in technical questions
        if ($questionOrder <= 6 && ($questionCounts['technical'] ?? 0) < 3) {
            return 'technical';
        }
        
        // Add situational questions
        if ($questionOrder <= 8 && ($questionCounts['situational'] ?? 0) < 2) {
            return 'situational';
        }
        
        // End with company culture fit
        return 'company_culture';
    }

    /**
     * Build prompt for question generation
     */
    protected function buildQuestionPrompt(array $context, string $questionType, int $questionOrder): string
    {
        $prompt = "Generate a {$questionType} interview question for a {$context['job_title']} position at {$context['company']}.\n\n";
        
        $prompt .= "Job Requirements: " . implode(', ', $context['job_requirements'] ?? []) . "\n";
        $prompt .= "Required Skills: " . implode(', ', $context['job_skills'] ?? []) . "\n";
        $prompt .= "Candidate Skills: " . implode(', ', $context['candidate_skills'] ?? []) . "\n\n";
        
        if (!empty($context['previous_questions'])) {
            $prompt .= "Previous questions asked:\n";
            foreach ($context['previous_questions'] as $pq) {
                $prompt .= "- {$pq['question']}\n";
            }
            $prompt .= "\n";
        }
        
        $prompt .= "This is question #{$questionOrder}. ";
        $prompt .= "Generate a unique, relevant {$questionType} question that hasn't been asked before. ";
        $prompt .= "The question should be professional, clear, and appropriate for the role level.";
        
        return $prompt;
    }

    /**
     * Analyze user's response using AI
     */
    protected function analyzeResponse(InterviewQuestion $question, string $response): array
    {
        $prompt = "Analyze this interview response:\n\n";
        $prompt .= "Question: {$question->question}\n";
        $prompt .= "Response: {$response}\n\n";
        $prompt .= "Provide analysis in JSON format with: relevance_score (0-10), clarity_score (0-10), completeness_score (0-10), key_points (array), concerns (array), needs_clarification (boolean)";

        try {
            $aiResponse = $this->openai->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert interview assessor. Analyze responses objectively and provide structured feedback.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 300,
                'temperature' => 0.3
            ]);

            return json_decode($aiResponse->choices[0]->message->content, true) ?? [];
        } catch (\Exception $e) {
            Log::error('Failed to analyze response', ['error' => $e->getMessage()]);
            return [
                'relevance_score' => 5,
                'clarity_score' => 5,
                'completeness_score' => 5,
                'key_points' => [],
                'concerns' => ['Analysis failed'],
                'needs_clarification' => false
            ];
        }
    }

    /**
     * Generate follow-up question if needed
     */
    protected function generateFollowUpIfNeeded(InterviewQuestion $question, string $response, array $analysis): ?string
    {
        if (!($analysis['needs_clarification'] ?? false)) {
            return null;
        }

        $prompt = "Based on this interview exchange, generate a brief follow-up question to clarify or dig deeper:\n\n";
        $prompt .= "Original Question: {$question->question}\n";
        $prompt .= "Response: {$response}\n";
        $prompt .= "Analysis concerns: " . implode(', ', $analysis['concerns'] ?? []) . "\n\n";
        $prompt .= "Generate a concise follow-up question (max 50 words).";

        try {
            $aiResponse = $this->openai->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'Generate brief, clarifying follow-up questions.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 100,
                'temperature' => 0.5
            ]);

            return trim($aiResponse->choices[0]->message->content);
        } catch (\Exception $e) {
            Log::error('Failed to generate follow-up', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Calculate overall interview score
     */
    protected function calculateOverallScore(Interview $interview): float
    {
        $questions = $interview->questions()->whereNotNull('analysis')->get();
        
        if ($questions->isEmpty()) {
            return 0.0;
        }

        $totalScore = 0;
        $count = 0;

        foreach ($questions as $question) {
            $analysis = $question->analysis;
            if (is_array($analysis)) {
                $questionScore = (
                    ($analysis['relevance_score'] ?? 0) +
                    ($analysis['clarity_score'] ?? 0) +
                    ($analysis['completeness_score'] ?? 0)
                ) / 3;
                
                $totalScore += $questionScore;
                $count++;
            }
        }

        return $count > 0 ? round($totalScore / $count, 2) : 0.0;
    }
}