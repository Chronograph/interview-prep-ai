<?php

namespace App\Services;

use App\Models\Interview;
use App\Models\InterviewQuestion;
use App\Models\Feedback;
use OpenAI;
use Illuminate\Support\Facades\Log;

class AIFeedbackService
{
    protected $openai;
    protected $feedbackTypes;

    public function __construct()
    {
        $this->openai = OpenAI::client(config('services.openai.api_key'));
        $this->feedbackTypes = [
            'overall' => 'Comprehensive interview performance analysis',
            'question_specific' => 'Individual question response analysis',
            'technical_skills' => 'Technical competency assessment',
            'communication' => 'Communication and presentation skills',
            'problem_solving' => 'Problem-solving approach and methodology'
        ];
    }

    /**
     * Generate comprehensive feedback for completed interview
     */
    public function generateComprehensiveFeedback(Interview $interview): array
    {
        if ($interview->status !== 'completed') {
            throw new \Exception('Interview must be completed before generating feedback');
        }

        $feedbackResults = [];

        // Generate overall interview feedback
        $overallFeedback = $this->generateOverallFeedback($interview);
        $feedbackResults['overall'] = $overallFeedback;

        // Generate question-specific feedback
        $questionFeedback = $this->generateQuestionSpecificFeedback($interview);
        $feedbackResults['questions'] = $questionFeedback;

        // Generate skill-based analysis
        $skillAnalysis = $this->generateSkillAnalysis($interview);
        $feedbackResults['skills'] = $skillAnalysis;

        // Generate improvement recommendations
        $recommendations = $this->generateImprovementRecommendations($interview);
        $feedbackResults['recommendations'] = $recommendations;

        return $feedbackResults;
    }

    /**
     * Generate overall interview feedback
     */
    public function generateOverallFeedback(Interview $interview): Feedback
    {
        $context = $this->buildFeedbackContext($interview);
        $prompt = $this->buildOverallFeedbackPrompt($context);

        try {
            $response = $this->openai->chat()->create([
                'model' => config('interview.ai_models.feedback_generation', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert interview coach and HR professional. Provide comprehensive, constructive feedback on interview performance. Be specific, actionable, and encouraging while maintaining professional standards.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.3
            ]);

            $feedbackData = $this->parseFeedbackResponse($response->choices[0]->message->content);

            return Feedback::create([
                'interview_id' => $interview->id,
                'feedback_type' => 'overall',
                'summary' => $feedbackData['summary'],
                'strengths' => $feedbackData['strengths'],
                'areas_for_improvement' => $feedbackData['areas_for_improvement'],
                'specific_suggestions' => $feedbackData['specific_suggestions'],
                'scores' => $feedbackData['scores'],
                'confidence_level' => $feedbackData['confidence_level'] ?? 0.8
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate overall feedback', [
                'interview_id' => $interview->id,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Failed to generate overall feedback: ' . $e->getMessage());
        }
    }

    /**
     * Generate feedback for individual questions
     */
    public function generateQuestionSpecificFeedback(Interview $interview): array
    {
        $questions = $interview->questions()->whereNotNull('user_response')->get();
        $feedbackResults = [];

        foreach ($questions as $question) {
            try {
                $feedback = $this->generateQuestionFeedback($question);
                $feedbackResults[] = $feedback;
            } catch (\Exception $e) {
                Log::error('Failed to generate question feedback', [
                    'question_id' => $question->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $feedbackResults;
    }

    /**
     * Generate feedback for a specific question
     */
    public function generateQuestionFeedback(InterviewQuestion $question): Feedback
    {
        $prompt = $this->buildQuestionFeedbackPrompt($question);

        try {
            $response = $this->openai->chat()->create([
                'model' => config('interview.ai_models.feedback_generation', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert interview assessor. Analyze individual question responses and provide detailed, constructive feedback.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.3
            ]);

            $feedbackData = $this->parseFeedbackResponse($response->choices[0]->message->content);

            return Feedback::create([
                'interview_id' => $question->interview_id,
                'interview_question_id' => $question->id,
                'feedback_type' => 'question_specific',
                'summary' => $feedbackData['summary'],
                'strengths' => $feedbackData['strengths'],
                'areas_for_improvement' => $feedbackData['areas_for_improvement'],
                'specific_suggestions' => $feedbackData['specific_suggestions'],
                'scores' => $feedbackData['scores'],
                'content_analysis' => $feedbackData['content_analysis'] ?? [],
                'confidence_level' => $feedbackData['confidence_level'] ?? 0.8
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate question feedback', [
                'question_id' => $question->id,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Failed to generate question feedback: ' . $e->getMessage());
        }
    }

    /**
     * Generate skill-based analysis
     */
    public function generateSkillAnalysis(Interview $interview): array
    {
        $context = $this->buildFeedbackContext($interview);
        $prompt = $this->buildSkillAnalysisPrompt($context);

        try {
            $response = $this->openai->chat()->create([
                'model' => config('interview.ai_models.feedback_generation', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a technical skills assessor. Analyze interview responses to evaluate specific technical and soft skills demonstrated by the candidate.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 800,
                'temperature' => 0.2
            ]);

            return json_decode($response->choices[0]->message->content, true) ?? [];

        } catch (\Exception $e) {
            Log::error('Failed to generate skill analysis', [
                'interview_id' => $interview->id,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Generate improvement recommendations
     */
    public function generateImprovementRecommendations(Interview $interview): array
    {
        $context = $this->buildFeedbackContext($interview);
        $prompt = $this->buildRecommendationsPrompt($context);

        try {
            $response = $this->openai->chat()->create([
                'model' => config('interview.ai_models.feedback_generation', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a career development coach. Provide specific, actionable recommendations for interview improvement based on performance analysis.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 600,
                'temperature' => 0.4
            ]);

            return json_decode($response->choices[0]->message->content, true) ?? [];

        } catch (\Exception $e) {
            Log::error('Failed to generate recommendations', [
                'interview_id' => $interview->id,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Build comprehensive feedback context
     */
    protected function buildFeedbackContext(Interview $interview): array
    {
        $jobPosting = $interview->jobPosting;
        $resume = $interview->resume;
        $questions = $interview->questions()->with('feedback')->get();

        return [
            'interview' => [
                'id' => $interview->id,
                'title' => $interview->title,
                'type' => $interview->interview_type,
                'duration' => $interview->duration_minutes,
                'overall_score' => $interview->overall_score,
                'started_at' => $interview->started_at,
                'completed_at' => $interview->completed_at
            ],
            'job' => [
                'title' => $jobPosting->title,
                'company' => $jobPosting->company,
                'requirements' => $jobPosting->requirements,
                'skills' => $jobPosting->skills,
                'experience_level' => $jobPosting->experience_level
            ],
            'candidate' => [
                'experience' => $resume->experience,
                'skills' => $resume->skills,
                'education' => $resume->education
            ],
            'questions' => $questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'type' => $q->question_type,
                    'response' => $q->user_response,
                    'analysis' => $q->analysis,
                    'response_time' => $q->response_time_seconds,
                    'follow_up' => $q->ai_follow_up
                ];
            })->toArray()
        ];
    }

    /**
     * Build prompt for overall feedback generation
     */
    protected function buildOverallFeedbackPrompt(array $context): string
    {
        $prompt = "Analyze this completed interview and provide comprehensive feedback:\n\n";
        
        $prompt .= "Job Position: {$context['job']['title']} at {$context['job']['company']}\n";
        $prompt .= "Interview Type: {$context['interview']['type']}\n";
        $prompt .= "Duration: {$context['interview']['duration']} minutes\n";
        $prompt .= "Overall Score: {$context['interview']['overall_score']}/10\n\n";
        
        $prompt .= "Questions and Responses:\n";
        foreach ($context['questions'] as $i => $q) {
            $prompt .= "Q" . ($i + 1) . ": {$q['question']}\n";
            $prompt .= "A" . ($i + 1) . ": {$q['response']}\n";
            if (!empty($q['analysis'])) {
                $prompt .= "Analysis: " . json_encode($q['analysis']) . "\n";
            }
            $prompt .= "\n";
        }
        
        $prompt .= "Provide feedback in JSON format with:\n";
        $prompt .= "- summary: Overall performance summary (2-3 sentences)\n";
        $prompt .= "- strengths: Array of key strengths demonstrated\n";
        $prompt .= "- areas_for_improvement: Array of areas needing improvement\n";
        $prompt .= "- specific_suggestions: Array of actionable improvement suggestions\n";
        $prompt .= "- scores: Object with communication, technical_knowledge, problem_solving, cultural_fit (0-10 each)\n";
        $prompt .= "- confidence_level: Confidence in assessment (0.0-1.0)";
        
        return $prompt;
    }

    /**
     * Build prompt for question-specific feedback
     */
    protected function buildQuestionFeedbackPrompt(InterviewQuestion $question): string
    {
        $prompt = "Analyze this specific interview question and response:\n\n";
        
        $prompt .= "Question Type: {$question->question_type}\n";
        $prompt .= "Question: {$question->question}\n";
        $prompt .= "Response: {$question->user_response}\n";
        $prompt .= "Response Time: {$question->response_time_seconds} seconds\n\n";
        
        if ($question->analysis) {
            $prompt .= "Previous Analysis: " . json_encode($question->analysis) . "\n\n";
        }
        
        $prompt .= "Provide detailed feedback in JSON format with:\n";
        $prompt .= "- summary: Brief assessment of the response quality\n";
        $prompt .= "- strengths: What was done well in this response\n";
        $prompt .= "- areas_for_improvement: Specific areas to improve\n";
        $prompt .= "- specific_suggestions: Actionable suggestions for better responses\n";
        $prompt .= "- scores: Object with relevance, clarity, depth, examples (0-10 each)\n";
        $prompt .= "- content_analysis: Detailed breakdown of response content";
        
        return $prompt;
    }

    /**
     * Build prompt for skill analysis
     */
    protected function buildSkillAnalysisPrompt(array $context): string
    {
        $prompt = "Analyze the technical and soft skills demonstrated in this interview:\n\n";
        
        $prompt .= "Required Skills: " . implode(', ', $context['job']['skills'] ?? []) . "\n";
        $prompt .= "Candidate Skills: " . implode(', ', $context['candidate']['skills'] ?? []) . "\n\n";
        
        $prompt .= "Interview Responses:\n";
        foreach ($context['questions'] as $i => $q) {
            $prompt .= "Q" . ($i + 1) . " ({$q['type']}): {$q['question']}\n";
            $prompt .= "A" . ($i + 1) . ": {$q['response']}\n\n";
        }
        
        $prompt .= "Provide skill analysis in JSON format with:\n";
        $prompt .= "- technical_skills: Object mapping each required skill to demonstrated level (0-10)\n";
        $prompt .= "- soft_skills: Object with communication, leadership, teamwork, adaptability, problem_solving (0-10 each)\n";
        $prompt .= "- skill_gaps: Array of skills not adequately demonstrated\n";
        $prompt .= "- skill_highlights: Array of exceptionally well-demonstrated skills";
        
        return $prompt;
    }

    /**
     * Build prompt for improvement recommendations
     */
    protected function buildRecommendationsPrompt(array $context): string
    {
        $prompt = "Based on this interview performance, provide specific improvement recommendations:\n\n";
        
        $prompt .= "Overall Score: {$context['interview']['overall_score']}/10\n";
        $prompt .= "Job Level: {$context['job']['experience_level']}\n\n";
        
        $prompt .= "Key Performance Areas:\n";
        foreach ($context['questions'] as $q) {
            if (!empty($q['analysis']['concerns'])) {
                $prompt .= "- Concerns: " . implode(', ', $q['analysis']['concerns']) . "\n";
            }
        }
        
        $prompt .= "\nProvide recommendations in JSON format with:\n";
        $prompt .= "- immediate_actions: Array of things to work on right away\n";
        $prompt .= "- practice_areas: Array of specific areas requiring practice\n";
        $prompt .= "- resources: Array of recommended learning resources or activities\n";
        $prompt .= "- mock_interview_focus: Array of question types to focus on in practice\n";
        $prompt .= "- timeline: Suggested improvement timeline with milestones";
        
        return $prompt;
    }

    /**
     * Parse AI feedback response into structured data
     */
    protected function parseFeedbackResponse(string $response): array
    {
        try {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to parse JSON feedback response', ['response' => $response]);
        }

        // Fallback parsing for non-JSON responses
        return [
            'summary' => 'Feedback analysis completed',
            'strengths' => ['Response provided'],
            'areas_for_improvement' => ['Analysis format needs improvement'],
            'specific_suggestions' => ['Review response structure'],
            'scores' => [
                'overall' => 5.0,
                'communication' => 5.0,
                'technical_knowledge' => 5.0,
                'problem_solving' => 5.0
            ],
            'confidence_level' => 0.5
        ];
    }
}