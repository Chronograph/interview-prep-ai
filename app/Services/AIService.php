<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Prism;
use Prism\Prism\ValueObjects\Messages\SystemMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class AIService
{
    private string $defaultModel;

    private Provider $provider;

    private float $temperature;

    public function __construct()
    {
        $providerName = config('prism.default_provider', 'lmstudio');
        $this->provider = match ($providerName) {
            'openai' => Provider::OpenAI,
            'anthropic' => Provider::Anthropic,
            'ollama' => Provider::Ollama,
            'lmstudio' => Provider::OpenAI, // LM Studio uses OpenAI-compatible API
            default => Provider::OpenAI
        };

        $this->defaultModel = config('prism.providers.'.$providerName.'.model', env('PRISM_LMSTUDIO_MODEL', 'phi-3.1-mini-4k-instruct'));
        $this->temperature = config('prism.temperature', 0.7);
    }

    public function generateResponse(string $prompt, ?string $systemPrompt = null): string
    {
        try {
            $messages = [];

            if ($systemPrompt) {
                $messages[] = new SystemMessage($systemPrompt);
            }

            $messages[] = new UserMessage($prompt);

            // Get provider configuration
            $providerName = config('prism.default_provider', 'lmstudio');
            $providerConfig = config("prism.providers.{$providerName}");

            $clientOptions = [
                'base_uri' => $providerConfig['url'] ?? null,
            ];

            // Note: Prism handles authentication automatically through its provider configuration
            // No need to manually add Authorization headers

            $response = Prism::text()
                ->using('openai', $this->defaultModel, $providerConfig)
                ->usingTemperature($this->temperature)
                ->withMessages($messages)
                ->generate();

            return trim($response->text);

        } catch (PrismException $e) {
            Log::error('AI API request failed', [
                'error' => $e->getMessage(),
                'model' => $this->defaultModel,
                'prompt_length' => strlen($prompt),
            ]);

            throw new \Exception('AI service unavailable: '.$e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Unexpected AI service error', [
                'error' => $e->getMessage(),
                'model' => $this->defaultModel,
                'prompt_length' => strlen($prompt),
            ]);

            throw new \Exception('AI service unavailable: '.$e->getMessage());
        }
    }

    public function generateChatResponse(
        array $messages,
        array $options = []
    ): array {
        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? $this->temperature;

        try {
            $prismMessages = [];
            foreach ($messages as $message) {
                if ($message['role'] === 'system') {
                    $prismMessages[] = new SystemMessage($message['content']);
                } elseif ($message['role'] === 'user') {
                    $prismMessages[] = new UserMessage($message['content']);
                }
            }

            $response = Prism::text()
                ->using($this->provider, $model)
                ->usingTemperature($temperature)
                ->withMessages($prismMessages)
                ->generate();

            return [
                'content' => trim($response->text),
                'usage' => [
                    'prompt_tokens' => $response->usage->promptTokens ?? 0,
                    'completion_tokens' => $response->usage->completionTokens ?? 0,
                    'total_tokens' => $response->usage->totalTokens ?? 0,
                ],
            ];
        } catch (PrismException $e) {
            Log::error('Prism API error: '.$e->getMessage());
            throw new \Exception('AI service temporarily unavailable. Please try again later.');
        } catch (\Throwable $e) {
            Log::error('Unexpected error in AI service: '.$e->getMessage());
            throw new \Exception('An unexpected error occurred. Please try again later.');
        }
    }

    public function analyzeResume(string $resumeText): array
    {
        $cacheKey = 'resume_analysis_'.md5($resumeText);

        return Cache::remember($cacheKey, 3600, function () use ($resumeText) {
            $prompt = "Analyze this resume and extract key information in JSON format:

{$resumeText}

Return JSON with:
{
    \"skills\": [\"skill1\", \"skill2\"],
    \"experience_years\": 5,
    \"education\": [{\"degree\": \"...\", \"school\": \"...\", \"year\": 2020}],
    \"certifications\": [\"cert1\", \"cert2\"],
    \"key_achievements\": [\"achievement1\", \"achievement2\"],
    \"suggested_improvements\": [\"improvement1\", \"improvement2\"],
    \"strength_areas\": [\"area1\", \"area2\"],
    \"missing_keywords\": [\"keyword1\", \"keyword2\"]
}";

            try {
                $response = $this->generateResponse($prompt, 'You are an expert resume analyzer. Provide detailed, actionable feedback in valid JSON format.');

                $analysis = json_decode($response, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON response');
                }

                return $analysis;

            } catch (\Exception $e) {
                Log::warning('Resume analysis failed', [
                    'error' => $e->getMessage(),
                    'resume_length' => strlen($resumeText),
                ]);

                return [
                    'skills' => [],
                    'experience_years' => 0,
                    'education' => [],
                    'certifications' => [],
                    'key_achievements' => [],
                    'suggested_improvements' => ['Unable to analyze resume'],
                    'strength_areas' => [],
                    'missing_keywords' => [],
                ];
            }
        });
    }

    public function generateInterviewQuestions(
        string $jobDescription,
        string $candidateProfile,
        string $questionType = 'behavioral'
    ): array {
        $prompt = "Generate interview questions based on:

Job Description: {$jobDescription}
Candidate Profile: {$candidateProfile}
Question Type: {$questionType}

Generate 5-8 relevant interview questions. Return as JSON array:
[
    {
        \"question\": \"...\",
        \"category\": \"behavioral|technical|situational\",
        \"difficulty\": \"easy|medium|hard\",
        \"expected_answer_points\": [\"point1\", \"point2\"]
    }
]";

        try {
            $response = $this->generateResponse($prompt, 'You are an expert interviewer. Generate relevant, insightful questions in valid JSON format.');

            $questions = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response');
            }

            return $questions ?? [];

        } catch (\Exception $e) {
            Log::warning('Question generation failed', [
                'error' => $e->getMessage(),
                'question_type' => $questionType,
            ]);

            return $this->getFallbackQuestions($questionType);
        }
    }

    public function evaluateAnswer(
        string $question,
        string $answer,
        array $context = []
    ): array {
        $contextStr = ! empty($context) ? json_encode($context) : '';

        $prompt = "Evaluate this interview answer:

Question: {$question}
Answer: {$answer}
Context: {$contextStr}

Provide evaluation in JSON format:
{
    \"score\": 1-10,
    \"strengths\": [\"strength1\", \"strength2\"],
    \"weaknesses\": [\"weakness1\", \"weakness2\"],
    \"suggestions\": [\"suggestion1\", \"suggestion2\"],
    \"overall_feedback\": \"Detailed feedback...\",
    \"key_missing_elements\": [\"element1\", \"element2\"]
}";

        try {
            $response = $this->generateResponse($prompt, 'You are an expert interview evaluator. Provide constructive, detailed feedback in valid JSON format.');

            $evaluation = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response');
            }

            return $evaluation;

        } catch (\Exception $e) {
            Log::warning('Answer evaluation failed', [
                'error' => $e->getMessage(),
                'question_length' => strlen($question),
                'answer_length' => strlen($answer),
            ]);

            return [
                'score' => 7,
                'strengths' => ['Clear communication'],
                'weaknesses' => ['Could provide more specific examples'],
                'suggestions' => ['Practice with concrete scenarios'],
                'overall_feedback' => 'Good answer with room for improvement.',
                'key_missing_elements' => [],
            ];
        }
    }

    public function generateCheatSheet(
        string $topic,
        string $userProfile,
        array $jobContext = []
    ): array {
        $contextStr = ! empty($jobContext) ? json_encode($jobContext) : '';

        $prompt = "Create a comprehensive cheat sheet for the interview topic: {$topic}

User Profile: {$userProfile}
Job Context: {$contextStr}

Generate a cheat sheet in JSON format:
{
    \"key_points\": [\"point1\", \"point2\"],
    \"suggested_response_framework\": \"STAR method: Situation, Task, Action, Result\",
    \"examples\": [
        {\"scenario\": \"...\", \"response\": \"...\"}
    ],
    \"dos\": [\"do1\", \"do2\"],
    \"donts\": [\"dont1\", \"dont2\"],
    \"follow_up_questions\": [\"question1\", \"question2\"],
    \"practice_scenarios\": [\"scenario1\", \"scenario2\"]
}";

        try {
            $response = $this->generateResponse($prompt, 'You are an expert career coach. Create comprehensive, actionable interview guidance in valid JSON format.');

            $cheatSheet = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response');
            }

            return $cheatSheet;

        } catch (\Exception $e) {
            Log::warning('Cheat sheet generation failed', [
                'error' => $e->getMessage(),
                'topic' => $topic,
            ]);

            return $this->getFallbackCheatSheet($topic);
        }
    }

    private function getFallbackQuestions(string $questionType): array
    {
        $fallbackQuestions = [
            'behavioral' => [
                [
                    'question' => 'Tell me about a time when you faced a challenging situation at work.',
                    'category' => 'behavioral',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Situation description', 'Actions taken', 'Results achieved'],
                ],
                [
                    'question' => 'Describe a time when you had to work with a difficult team member.',
                    'category' => 'behavioral',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Conflict description', 'Resolution approach', 'Outcome'],
                ],
            ],
            'technical' => [
                [
                    'question' => 'Explain your approach to solving complex technical problems.',
                    'category' => 'technical',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Problem analysis', 'Solution methodology', 'Implementation'],
                ],
            ],
        ];

        return $fallbackQuestions[$questionType] ?? $fallbackQuestions['behavioral'];
    }

    private function getFallbackCheatSheet(string $topic): array
    {
        return [
            'key_points' => [
                'Prepare specific examples',
                'Use the STAR method',
                'Practice your delivery',
            ],
            'suggested_response_framework' => 'STAR method: Situation, Task, Action, Result',
            'examples' => [
                [
                    'scenario' => 'General interview question',
                    'response' => 'Use specific examples from your experience',
                ],
            ],
            'dos' => [
                'Be specific and concrete',
                'Show measurable results',
                'Stay positive',
            ],
            'donts' => [
                'Don\'t be vague',
                'Don\'t speak negatively about past employers',
                'Don\'t make up examples',
            ],
            'follow_up_questions' => [
                'Can you provide another example?',
                'How would you handle this differently now?',
            ],
            'practice_scenarios' => [
                'Practice with a friend',
                'Record yourself answering',
            ],
        ];
    }
}
