<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AIService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1';
    private string $defaultModel = 'gpt-4';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        
        if (!$this->apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }
    }

    public function generateResponse(
        string $prompt,
        array $options = []
    ): string {
        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? 1000;
        $systemPrompt = $options['system_prompt'] ?? null;

        $messages = [];
        
        if ($systemPrompt) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }
        
        $messages[] = ['role' => 'user', 'content' => $prompt];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if (!$response->successful()) {
                throw new \Exception('OpenAI API request failed: ' . $response->body());
            }

            $data = $response->json();
            
            if (!isset($data['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid response format from OpenAI API');
            }

            return trim($data['choices'][0]['message']['content']);

        } catch (\Exception $e) {
            Log::error('OpenAI API request failed', [
                'error' => $e->getMessage(),
                'model' => $model,
                'prompt_length' => strlen($prompt),
            ]);
            
            throw new \Exception('AI service unavailable: ' . $e->getMessage());
        }
    }

    public function generateChatResponse(
        array $messages,
        array $options = []
    ): array {
        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? 1000;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if (!$response->successful()) {
                throw new \Exception('OpenAI API request failed: ' . $response->body());
            }

            $data = $response->json();
            
            return [
                'content' => $data['choices'][0]['message']['content'] ?? '',
                'usage' => $data['usage'] ?? [],
                'model' => $data['model'] ?? $model,
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI chat API request failed', [
                'error' => $e->getMessage(),
                'model' => $model,
                'messages_count' => count($messages),
            ]);
            
            throw new \Exception('AI chat service unavailable: ' . $e->getMessage());
        }
    }

    public function analyzeResume(string $resumeText): array
    {
        $cacheKey = 'resume_analysis_' . md5($resumeText);
        
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
                $response = $this->generateResponse($prompt, [
                    'temperature' => 0.3,
                    'max_tokens' => 1500,
                ]);
                
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
            $response = $this->generateResponse($prompt, [
                'temperature' => 0.7,
                'max_tokens' => 1200,
            ]);
            
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
        $contextStr = !empty($context) ? json_encode($context) : '';
        
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
            $response = $this->generateResponse($prompt, [
                'temperature' => 0.3,
                'max_tokens' => 800,
            ]);
            
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
        $contextStr = !empty($jobContext) ? json_encode($jobContext) : '';
        
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
            $response = $this->generateResponse($prompt, [
                'temperature' => 0.6,
                'max_tokens' => 1500,
            ]);
            
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
                    'expected_answer_points' => ['Situation description', 'Actions taken', 'Results achieved']
                ],
                [
                    'question' => 'Describe a time when you had to work with a difficult team member.',
                    'category' => 'behavioral',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Conflict description', 'Resolution approach', 'Outcome']
                ]
            ],
            'technical' => [
                [
                    'question' => 'Explain your approach to solving complex technical problems.',
                    'category' => 'technical',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Problem analysis', 'Solution methodology', 'Implementation']
                ]
            ]
        ];
        
        return $fallbackQuestions[$questionType] ?? $fallbackQuestions['behavioral'];
    }

    private function getFallbackCheatSheet(string $topic): array
    {
        return [
            'key_points' => [
                'Prepare specific examples',
                'Use the STAR method',
                'Practice your delivery'
            ],
            'suggested_response_framework' => 'STAR method: Situation, Task, Action, Result',
            'examples' => [
                [
                    'scenario' => 'General interview question',
                    'response' => 'Use specific examples from your experience'
                ]
            ],
            'dos' => [
                'Be specific and concrete',
                'Show measurable results',
                'Stay positive'
            ],
            'donts' => [
                'Don\'t be vague',
                'Don\'t speak negatively about past employers',
                'Don\'t make up examples'
            ],
            'follow_up_questions' => [
                'Can you provide another example?',
                'How would you handle this differently now?'
            ],
            'practice_scenarios' => [
                'Practice with a friend',
                'Record yourself answering'
            ]
        ];
    }
}