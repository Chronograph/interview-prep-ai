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
        $providerName = config('prism.default_provider', 'openai'); // Changed default to OpenAI
        $this->provider = match ($providerName) {
            'openai' => Provider::OpenAI,
            'anthropic' => Provider::Anthropic,
            'ollama' => Provider::Ollama,
            'lmstudio' => Provider::OpenAI, // LM Studio uses OpenAI-compatible API
            default => Provider::OpenAI
        };

        $this->defaultModel = config('prism.providers.'.$providerName.'.model', env('PRISM_OPENAI_MODEL', 'gpt-4'));
        $this->temperature = config('prism.temperature', 0.7);
    }

    public function generateResponse(string $prompt, ?string $systemPrompt = null): string
    {
        // EMERGENCY: Disable all HTTP calls to prevent timeouts
        Log::warning('AI service generateResponse called but disabled to prevent timeouts', [
            'prompt_length' => strlen($prompt),
            'system_prompt_length' => $systemPrompt ? strlen($systemPrompt) : 0,
        ]);
        
        throw new \Exception('AI service temporarily disabled to prevent timeout issues');
        
        /* HTTP calls disabled due to persistent timeout issues
        try {
            $messages = [];

            if ($systemPrompt) {
                $messages[] = new SystemMessage($systemPrompt);
            }

            $messages[] = new UserMessage($prompt);

            // Get provider configuration
            $providerName = config('prism.default_provider', 'openai');
            $providerConfig = config("prism.providers.{$providerName}");

            $clientOptions = [
                'base_uri' => $providerConfig['url'] ?? null,
                'timeout' => 15, // 15 second timeout to prevent PHP script timeout
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
        */
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
        // Get interview type specific instructions
        $typeInstructions = $this->getInterviewTypeInstructions($questionType);
        
        $prompt = "Generate a representative mix of interview questions based on:

Job Description: {$jobDescription}
Candidate Profile: {$candidateProfile}
Interview Type: {$questionType}

Interview Type Instructions:
{$typeInstructions}

Create a realistic mix of interview questions that a job seeker would actually encounter in this type of interview for this role. The questions should:
- Cover different aspects of the role and requirements
- Include a variety of question styles (open-ended, scenario-based, technical, behavioral)
- Range from foundational to advanced based on the role level
- Be specific to the job and company context when possible

Generate 5-8 questions that represent the typical interview experience. Return as JSON array:
[
    {
        \"question\": \"...\",
        \"category\": \"behavioral|technical|situational|cultural_fit|experience\",
        \"difficulty\": \"easy|medium|hard\",
        \"expected_answer_points\": [\"point1\", \"point2\"],
        \"question_style\": \"open_ended|scenario|technical_challenge|behavioral_star\"
    }
]";

        // EMERGENCY: Disable all AI service calls to prevent timeouts
        // TODO: Fix HTTP client timeout configuration before re-enabling
        Log::info('Using fallback questions (AI service disabled due to timeout issues)', [
            'question_type' => $questionType,
            'job_description_length' => strlen($jobDescription),
            'candidate_profile_length' => strlen($candidateProfile),
        ]);
        
        return $this->getFallbackQuestions($questionType);
        
        /* AI Service calls disabled due to persistent timeout issues
        try {
            Log::info('Generating interview questions with OpenAI', [
                'question_type' => $questionType,
                'job_description_length' => strlen($jobDescription),
                'candidate_profile_length' => strlen($candidateProfile),
            ]);
            
            $response = $this->generateResponse($prompt, 'You are an expert interviewer. Generate relevant, insightful questions in valid JSON format.');

            $questions = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response');
            }

            Log::info('OpenAI-generated questions received', [
                'question_type' => $questionType,
                'questions_count' => count($questions ?? []),
            ]);

            return $questions ?? [];

        } catch (\Exception $e) {
            Log::warning('OpenAI question generation failed, using fallback', [
                'error' => $e->getMessage(),
                'question_type' => $questionType,
            ]);

            return $this->getFallbackQuestions($questionType);
        }
        */
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
                    'question' => 'Tell me about a time when you faced a challenging situation at work and how you overcame it.',
                    'category' => 'behavioral',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Situation description', 'Actions taken', 'Results achieved'],
                    'question_style' => 'behavioral_star',
                ],
                [
                    'question' => 'Describe a time when you had to work with a difficult team member. How did you handle it?',
                    'category' => 'behavioral',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Conflict description', 'Resolution approach', 'Outcome'],
                    'question_style' => 'behavioral_star',
                ],
                [
                    'question' => 'Tell me about your greatest professional achievement and what made it significant.',
                    'category' => 'behavioral',
                    'difficulty' => 'easy',
                    'expected_answer_points' => ['Achievement description', 'Challenges faced', 'Impact made'],
                    'question_style' => 'behavioral_star',
                ],
                [
                    'question' => 'Describe a situation where you had to learn something new quickly to complete a project.',
                    'category' => 'behavioral',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Learning approach', 'Time management', 'Results'],
                    'question_style' => 'behavioral_star',
                ],
            ],
            'technical' => [
                [
                    'question' => 'Explain your approach to solving complex technical problems.',
                    'category' => 'technical',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Problem analysis', 'Solution methodology', 'Implementation'],
                    'question_style' => 'technical_challenge',
                ],
                [
                    'question' => 'How would you design a scalable system architecture?',
                    'category' => 'technical',
                    'difficulty' => 'hard',
                    'expected_answer_points' => ['Architecture principles', 'Scalability considerations', 'Trade-offs'],
                    'question_style' => 'technical_challenge',
                ],
                [
                    'question' => 'Describe your experience with debugging and troubleshooting.',
                    'category' => 'technical',
                    'difficulty' => 'easy',
                    'expected_answer_points' => ['Debugging methodology', 'Tools used', 'Problem-solving approach'],
                    'question_style' => 'technical_challenge',
                ],
            ],
            'case_study' => [
                [
                    'question' => 'How would you approach launching a new product in a competitive market?',
                    'category' => 'case_study',
                    'difficulty' => 'hard',
                    'expected_answer_points' => ['Market analysis', 'Strategy development', 'Implementation plan'],
                    'question_style' => 'scenario',
                ],
                [
                    'question' => 'A client is unhappy with your product. How would you handle this situation?',
                    'category' => 'case_study',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Problem identification', 'Communication strategy', 'Resolution plan'],
                    'question_style' => 'scenario',
                ],
                [
                    'question' => 'How would you prioritize features for a product roadmap?',
                    'category' => 'case_study',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Criteria for prioritization', 'Stakeholder considerations', 'Decision framework'],
                    'question_style' => 'scenario',
                ],
            ],
            'company_specific' => [
                [
                    'question' => 'Why do you want to work for our company specifically?',
                    'category' => 'cultural_fit',
                    'difficulty' => 'easy',
                    'expected_answer_points' => ['Company research', 'Values alignment', 'Career goals'],
                    'question_style' => 'open_ended',
                ],
                [
                    'question' => 'What do you know about our company culture and how do you see yourself fitting in?',
                    'category' => 'cultural_fit',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Culture understanding', 'Personal fit', 'Contribution potential'],
                    'question_style' => 'open_ended',
                ],
                [
                    'question' => 'How do you stay updated with industry trends relevant to our business?',
                    'category' => 'experience',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Learning methods', 'Industry knowledge', 'Continuous improvement'],
                    'question_style' => 'open_ended',
                ],
            ],
            'elevator_pitch' => [
                [
                    'question' => 'Tell me about yourself in 2 minutes.',
                    'category' => 'experience',
                    'difficulty' => 'easy',
                    'expected_answer_points' => ['Professional background', 'Key achievements', 'Career goals'],
                    'question_style' => 'open_ended',
                ],
                [
                    'question' => 'What makes you unique compared to other candidates?',
                    'category' => 'experience',
                    'difficulty' => 'medium',
                    'expected_answer_points' => ['Unique strengths', 'Differentiating factors', 'Value proposition'],
                    'question_style' => 'open_ended',
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

    /**
     * Get interview type specific instructions for question generation
     */
    private function getInterviewTypeInstructions(string $interviewType): string
    {
        return match($interviewType) {
            'behavioral' => 'Generate a representative mix of behavioral questions that assess past experiences, leadership, teamwork, problem-solving, and cultural fit. Include questions about: handling difficult situations, leadership experiences, teamwork challenges, failure and learning, conflict resolution, and achievement stories. Use the STAR method (Situation, Task, Action, Result) framework. Mix open-ended questions ("Tell me about a time when...") with more specific scenarios ("Describe a situation where...").',
            
            'technical' => 'Generate a representative mix of technical questions that assess job-specific skills, knowledge, and problem-solving abilities. Include: foundational knowledge questions, hands-on problem-solving scenarios, system design challenges, debugging exercises, and practical application questions. Mix theoretical concepts with real-world implementation challenges. Vary the difficulty from basic understanding to complex problem-solving.',
            
            'case_study' => 'Generate a representative mix of case study questions that assess analytical thinking, business acumen, and problem-solving approach. Include: business strategy scenarios, data analysis problems, market entry challenges, operational improvements, and financial analysis cases. Present realistic business situations that require structured thinking, hypothesis formation, and solution development.',
            
            'elevator_pitch' => 'Generate a representative mix of communication and presentation questions. Include: self-introduction scenarios, explaining complex topics simply, handling difficult questions, presenting ideas to stakeholders, and demonstrating confidence under pressure. Mix structured presentations with impromptu speaking challenges.',
            
            'company_specific' => 'Generate a representative mix of company-specific questions that assess knowledge of the company, industry, culture, and recent developments. Include: questions about company mission/values, industry knowledge, competitive landscape, recent company news, role-specific company context, and cultural fit assessment. Balance general company knowledge with role-specific insights.',
            
            'skill_focused' => 'Generate a representative mix of questions that assess specific skills mentioned in the job requirements. Include: foundational skill questions, advanced application scenarios, tool-specific challenges, methodology questions, and practical implementation exercises. Ensure questions test both theoretical knowledge and practical application of the specific competencies.',
            
            default => 'Generate a representative mix of general interview questions that assess overall fit for the role, including experience, motivation, problem-solving ability, and cultural alignment. Include questions about career goals, role understanding, motivation, strengths/weaknesses, and situational judgment.'
        };
    }
}
