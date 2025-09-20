<?php

namespace Tests\Unit;

use App\Services\AIInterviewService;
use App\Models\JobPosting;
use App\Models\Resume;
use App\Models\User;
use App\Models\Interview;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Mockery;

class AIInterviewServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $aiService;
    protected $user;
    protected $jobPosting;
    protected $resume;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->aiService = new AIInterviewService();
        $this->user = User::factory()->create();
        $this->jobPosting = JobPosting::factory()->create(['user_id' => $this->user->id]);
        $this->resume = Resume::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_can_generate_interview_questions()
    {
        // Mock the HTTP response from OpenAI
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                [
                                    'question' => 'Tell me about your experience with PHP.',
                                    'category' => 'technical',
                                    'difficulty' => 'medium',
                                    'expected_duration' => 300
                                ],
                                [
                                    'question' => 'Describe a challenging project you worked on.',
                                    'category' => 'behavioral',
                                    'difficulty' => 'medium',
                                    'expected_duration' => 240
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $questions = $this->aiService->generateInterviewQuestions(
            $this->jobPosting,
            $this->resume,
            'technical',
            'medium'
        );

        $this->assertIsArray($questions);
        $this->assertCount(2, $questions);
        $this->assertArrayHasKey('question', $questions[0]);
        $this->assertArrayHasKey('category', $questions[0]);
        $this->assertArrayHasKey('difficulty', $questions[0]);
    }

    public function test_can_analyze_response_quality()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'score' => 85,
                                'feedback' => 'Good technical explanation with room for improvement in examples.',
                                'strengths' => ['Clear communication', 'Technical accuracy'],
                                'improvements' => ['Add more specific examples', 'Elaborate on edge cases'],
                                'category_scores' => [
                                    'technical_knowledge' => 90,
                                    'communication' => 80,
                                    'problem_solving' => 85
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $question = Question::factory()->create();
        $response = Response::factory()->create([
            'question_id' => $question->id,
            'text_response' => 'This is a sample response to analyze.'
        ]);

        $analysis = $this->aiService->analyzeResponse($response);

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('score', $analysis);
        $this->assertArrayHasKey('feedback', $analysis);
        $this->assertArrayHasKey('strengths', $analysis);
        $this->assertArrayHasKey('improvements', $analysis);
        $this->assertEquals(85, $analysis['score']);
    }

    public function test_can_generate_personalized_feedback()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Based on your responses, you demonstrate strong technical skills but could improve your communication of complex concepts. Focus on providing more concrete examples in future interviews.'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $interview = Interview::factory()->create(['user_id' => $this->user->id]);
        $questions = Question::factory()->count(3)->create(['interview_id' => $interview->id]);
        
        foreach ($questions as $question) {
            Response::factory()->create([
                'question_id' => $question->id,
                'user_id' => $this->user->id,
                'ai_score' => rand(70, 95)
            ]);
        }

        $feedback = $this->aiService->generatePersonalizedFeedback($interview);

        $this->assertIsString($feedback);
        $this->assertNotEmpty($feedback);
    }

    public function test_can_extract_resume_skills()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'technical_skills' => ['PHP', 'Laravel', 'JavaScript', 'MySQL'],
                                'soft_skills' => ['Leadership', 'Communication', 'Problem Solving'],
                                'experience_level' => 'Senior',
                                'key_achievements' => [
                                    'Led team of 5 developers',
                                    'Improved system performance by 40%'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeContent = 'Senior PHP Developer with 5 years experience...';
        $skills = $this->aiService->extractResumeSkills($resumeContent);

        $this->assertIsArray($skills);
        $this->assertArrayHasKey('technical_skills', $skills);
        $this->assertArrayHasKey('soft_skills', $skills);
        $this->assertArrayHasKey('experience_level', $skills);
    }

    public function test_can_suggest_interview_improvements()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'overall_assessment' => 'Good performance with room for improvement',
                                'strengths' => [
                                    'Strong technical knowledge',
                                    'Clear communication'
                                ],
                                'areas_for_improvement' => [
                                    'Provide more specific examples',
                                    'Practice behavioral questions'
                                ],
                                'recommended_resources' => [
                                    'STAR method for behavioral questions',
                                    'System design practice'
                                ],
                                'next_steps' => [
                                    'Practice with mock interviews',
                                    'Review common algorithms'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $interview = Interview::factory()->create(['user_id' => $this->user->id]);
        $suggestions = $this->aiService->suggestImprovements($interview);

        $this->assertIsArray($suggestions);
        $this->assertArrayHasKey('overall_assessment', $suggestions);
        $this->assertArrayHasKey('strengths', $suggestions);
        $this->assertArrayHasKey('areas_for_improvement', $suggestions);
    }

    public function test_handles_api_errors_gracefully()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([], 500)
        ]);

        $questions = $this->aiService->generateInterviewQuestions(
            $this->jobPosting,
            $this->resume,
            'technical',
            'medium'
        );

        // Should return fallback questions or empty array
        $this->assertIsArray($questions);
    }

    public function test_validates_input_parameters()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->aiService->generateInterviewQuestions(
            $this->jobPosting,
            $this->resume,
            'invalid_type', // Invalid interview type
            'medium'
        );
    }

    public function test_can_generate_follow_up_questions()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                [
                                    'question' => 'Can you elaborate on the specific technologies you used?',
                                    'category' => 'technical',
                                    'difficulty' => 'medium'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $originalQuestion = Question::factory()->create();
        $response = Response::factory()->create([
            'question_id' => $originalQuestion->id,
            'text_response' => 'I worked on a web application using modern frameworks.'
        ]);

        $followUpQuestions = $this->aiService->generateFollowUpQuestions($response);

        $this->assertIsArray($followUpQuestions);
        $this->assertNotEmpty($followUpQuestions);
    }

    public function test_can_calculate_interview_difficulty()
    {
        $interview = Interview::factory()->create(['user_id' => $this->user->id]);
        
        // Create questions with different difficulties
        Question::factory()->create(['interview_id' => $interview->id, 'difficulty' => 'easy']);
        Question::factory()->create(['interview_id' => $interview->id, 'difficulty' => 'medium']);
        Question::factory()->create(['interview_id' => $interview->id, 'difficulty' => 'hard']);

        $difficulty = $this->aiService->calculateInterviewDifficulty($interview);

        $this->assertIsString($difficulty);
        $this->assertContains($difficulty, ['easy', 'medium', 'hard']);
    }

    public function test_can_generate_practice_scenarios()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                [
                                    'scenario' => 'You need to optimize a slow database query',
                                    'context' => 'E-commerce application with high traffic',
                                    'expected_approach' => 'Analyze query execution plan, add indexes, consider caching'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $scenarios = $this->aiService->generatePracticeScenarios(
            $this->jobPosting,
            'technical',
            3
        );

        $this->assertIsArray($scenarios);
        $this->assertNotEmpty($scenarios);
        $this->assertArrayHasKey('scenario', $scenarios[0]);
    }

    public function test_respects_rate_limiting()
    {
        // Mock multiple rapid requests
        Http::fake([
            'api.openai.com/*' => Http::sequence()
                ->push([], 200)
                ->push([], 429) // Rate limited
                ->push([], 200)
        ]);

        // First request should succeed
        $result1 = $this->aiService->generateInterviewQuestions(
            $this->jobPosting,
            $this->resume,
            'technical',
            'medium'
        );

        // Second request should handle rate limiting
        $result2 = $this->aiService->generateInterviewQuestions(
            $this->jobPosting,
            $this->resume,
            'technical',
            'medium'
        );

        $this->assertIsArray($result1);
        $this->assertIsArray($result2);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}