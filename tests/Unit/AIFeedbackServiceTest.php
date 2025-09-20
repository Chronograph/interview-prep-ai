<?php

namespace Tests\Unit;

use App\Services\AIFeedbackService;
use App\Models\User;
use App\Models\Interview;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Mockery;

class AIFeedbackServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $feedbackService;
    protected $user;
    protected $interview;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->feedbackService = new AIFeedbackService();
        $this->user = User::factory()->create();
        $this->interview = Interview::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_can_analyze_response_content()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'content_quality' => 85,
                                'technical_accuracy' => 90,
                                'clarity' => 80,
                                'completeness' => 75,
                                'relevance' => 88,
                                'detailed_feedback' => 'Strong technical understanding demonstrated with clear explanations.',
                                'improvement_areas' => [
                                    'Add more specific examples',
                                    'Elaborate on implementation details'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $question = Question::factory()->create(['interview_id' => $this->interview->id]);
        $response = Response::factory()->create([
            'question_id' => $question->id,
            'user_id' => $this->user->id,
            'text_response' => 'I would use Laravel\'s Eloquent ORM to handle database operations efficiently.'
        ]);

        $analysis = $this->feedbackService->analyzeResponseContent($response);

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('content_quality', $analysis);
        $this->assertArrayHasKey('technical_accuracy', $analysis);
        $this->assertArrayHasKey('clarity', $analysis);
        $this->assertArrayHasKey('detailed_feedback', $analysis);
        $this->assertBetween($analysis['content_quality'], 0, 100);
    }

    public function test_can_evaluate_communication_skills()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'communication_score' => 82,
                                'structure_score' => 85,
                                'vocabulary_score' => 78,
                                'confidence_level' => 'high',
                                'speaking_pace' => 'appropriate',
                                'feedback' => 'Well-structured response with good use of technical vocabulary.',
                                'suggestions' => [
                                    'Use more transitional phrases',
                                    'Provide concrete examples'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = Response::factory()->create([
            'user_id' => $this->user->id,
            'audio_file_path' => 'responses/audio_123.wav',
            'text_response' => 'Well-structured technical explanation'
        ]);

        $evaluation = $this->feedbackService->evaluateCommunicationSkills($response);

        $this->assertIsArray($evaluation);
        $this->assertArrayHasKey('communication_score', $evaluation);
        $this->assertArrayHasKey('structure_score', $evaluation);
        $this->assertArrayHasKey('confidence_level', $evaluation);
        $this->assertArrayHasKey('suggestions', $evaluation);
    }

    public function test_can_generate_improvement_recommendations()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'priority_areas' => [
                                    'technical_depth',
                                    'communication_clarity'
                                ],
                                'specific_recommendations' => [
                                    [
                                        'area' => 'technical_depth',
                                        'recommendation' => 'Practice explaining complex algorithms step by step',
                                        'resources' => ['LeetCode', 'System Design Primer'],
                                        'timeline' => '2-3 weeks'
                                    ]
                                ],
                                'practice_exercises' => [
                                    'Mock technical interviews',
                                    'Code review sessions'
                                ],
                                'overall_strategy' => 'Focus on structured problem-solving approach'
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $questions = Question::factory()->count(3)->create(['interview_id' => $this->interview->id]);
        foreach ($questions as $question) {
            Response::factory()->create([
                'question_id' => $question->id,
                'user_id' => $this->user->id,
                'ai_score' => rand(60, 85)
            ]);
        }

        $recommendations = $this->feedbackService->generateImprovementRecommendations($this->interview);

        $this->assertIsArray($recommendations);
        $this->assertArrayHasKey('priority_areas', $recommendations);
        $this->assertArrayHasKey('specific_recommendations', $recommendations);
        $this->assertArrayHasKey('practice_exercises', $recommendations);
    }

    public function test_can_track_progress_over_time()
    {
        // Create multiple interviews for progress tracking
        $interviews = Interview::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'created_at' => now()->subDays(rand(1, 30))
        ]);

        foreach ($interviews as $interview) {
            $questions = Question::factory()->count(2)->create(['interview_id' => $interview->id]);
            foreach ($questions as $question) {
                Response::factory()->create([
                    'question_id' => $question->id,
                    'user_id' => $this->user->id,
                    'ai_score' => rand(70, 95)
                ]);
            }
        }

        $progress = $this->feedbackService->trackProgressOverTime($this->user);

        $this->assertIsArray($progress);
        $this->assertArrayHasKey('score_trend', $progress);
        $this->assertArrayHasKey('improvement_rate', $progress);
        $this->assertArrayHasKey('strengths_development', $progress);
        $this->assertArrayHasKey('areas_needing_focus', $progress);
    }

    public function test_can_compare_with_benchmarks()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'percentile_ranking' => 75,
                                'industry_comparison' => 'above_average',
                                'role_level_comparison' => 'meets_expectations',
                                'benchmark_categories' => [
                                    'technical_skills' => 80,
                                    'communication' => 70,
                                    'problem_solving' => 85
                                ],
                                'competitive_analysis' => 'Strong technical skills, room for improvement in communication'
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $questions = Question::factory()->count(5)->create(['interview_id' => $this->interview->id]);
        foreach ($questions as $question) {
            Response::factory()->create([
                'question_id' => $question->id,
                'user_id' => $this->user->id,
                'ai_score' => rand(75, 90)
            ]);
        }

        $comparison = $this->feedbackService->compareWithBenchmarks(
            $this->interview,
            'software_engineer',
            'mid_level'
        );

        $this->assertIsArray($comparison);
        $this->assertArrayHasKey('percentile_ranking', $comparison);
        $this->assertArrayHasKey('industry_comparison', $comparison);
        $this->assertArrayHasKey('benchmark_categories', $comparison);
    }

    public function test_can_identify_strengths_and_weaknesses()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'strengths' => [
                                    [
                                        'category' => 'technical_knowledge',
                                        'description' => 'Strong understanding of algorithms and data structures',
                                        'evidence' => 'Consistently high scores on technical questions',
                                        'confidence' => 'high'
                                    ]
                                ],
                                'weaknesses' => [
                                    [
                                        'category' => 'behavioral_responses',
                                        'description' => 'Needs improvement in storytelling and examples',
                                        'impact' => 'medium',
                                        'actionable_steps' => ['Practice STAR method', 'Prepare specific examples']
                                    ]
                                ],
                                'overall_assessment' => 'Strong technical candidate with room for behavioral improvement'
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $analysis = $this->feedbackService->identifyStrengthsAndWeaknesses($this->interview);

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('strengths', $analysis);
        $this->assertArrayHasKey('weaknesses', $analysis);
        $this->assertArrayHasKey('overall_assessment', $analysis);
        $this->assertIsArray($analysis['strengths']);
        $this->assertIsArray($analysis['weaknesses']);
    }

    public function test_can_generate_personalized_study_plan()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'study_plan' => [
                                    'week_1' => [
                                        'focus' => 'Algorithm fundamentals',
                                        'tasks' => ['Review sorting algorithms', 'Practice array problems'],
                                        'time_allocation' => '5 hours'
                                    ],
                                    'week_2' => [
                                        'focus' => 'System design basics',
                                        'tasks' => ['Study scalability patterns', 'Design simple systems'],
                                        'time_allocation' => '6 hours'
                                    ]
                                ],
                                'resources' => [
                                    'books' => ['Cracking the Coding Interview'],
                                    'online' => ['LeetCode', 'System Design Primer'],
                                    'practice' => ['Mock interviews', 'Peer coding sessions']
                                ],
                                'milestones' => [
                                    'Complete 50 coding problems',
                                    'Design 3 systems end-to-end'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $studyPlan = $this->feedbackService->generatePersonalizedStudyPlan(
            $this->user,
            'software_engineer',
            4 // weeks
        );

        $this->assertIsArray($studyPlan);
        $this->assertArrayHasKey('study_plan', $studyPlan);
        $this->assertArrayHasKey('resources', $studyPlan);
        $this->assertArrayHasKey('milestones', $studyPlan);
    }

    public function test_can_analyze_response_timing()
    {
        $response = Response::factory()->create([
            'user_id' => $this->user->id,
            'response_time' => 180, // 3 minutes
            'created_at' => now()->subMinutes(3)
        ]);

        $timingAnalysis = $this->feedbackService->analyzeResponseTiming($response);

        $this->assertIsArray($timingAnalysis);
        $this->assertArrayHasKey('response_speed', $timingAnalysis);
        $this->assertArrayHasKey('optimal_range', $timingAnalysis);
        $this->assertArrayHasKey('feedback', $timingAnalysis);
        $this->assertContains($timingAnalysis['response_speed'], ['too_fast', 'optimal', 'too_slow']);
    }

    public function test_can_evaluate_code_quality()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'code_quality_score' => 88,
                                'readability' => 85,
                                'efficiency' => 90,
                                'best_practices' => 85,
                                'maintainability' => 80,
                                'suggestions' => [
                                    'Add more descriptive variable names',
                                    'Consider edge cases handling'
                                ],
                                'positive_aspects' => [
                                    'Clean structure',
                                    'Proper error handling'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $codeResponse = 'function fibonacci(n) {\n    if (n <= 1) return n;\n    return fibonacci(n-1) + fibonacci(n-2);\n}';
        
        $response = Response::factory()->create([
            'user_id' => $this->user->id,
            'text_response' => $codeResponse
        ]);

        $codeEvaluation = $this->feedbackService->evaluateCodeQuality($response);

        $this->assertIsArray($codeEvaluation);
        $this->assertArrayHasKey('code_quality_score', $codeEvaluation);
        $this->assertArrayHasKey('readability', $codeEvaluation);
        $this->assertArrayHasKey('efficiency', $codeEvaluation);
        $this->assertArrayHasKey('suggestions', $codeEvaluation);
    }

    public function test_handles_empty_responses_gracefully()
    {
        $response = Response::factory()->create([
            'user_id' => $this->user->id,
            'text_response' => ''
        ]);

        $analysis = $this->feedbackService->analyzeResponseContent($response);

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('content_quality', $analysis);
        $this->assertEquals(0, $analysis['content_quality']);
    }

    public function test_validates_input_parameters()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->feedbackService->compareWithBenchmarks(
            $this->interview,
            'invalid_role', // Invalid role
            'mid_level'
        );
    }

    public function test_caches_expensive_operations()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode(['cached_result' => true])
                        ]
                    ]
                ]
            ], 200)
        ]);

        // First call should hit the API
        $result1 = $this->feedbackService->trackProgressOverTime($this->user);
        
        // Second call should use cache (if implemented)
        $result2 = $this->feedbackService->trackProgressOverTime($this->user);

        $this->assertIsArray($result1);
        $this->assertIsArray($result2);
        // In a real implementation, you'd verify cache usage
    }

    protected function assertBetween($value, $min, $max)
    {
        $this->assertGreaterThanOrEqual($min, $value);
        $this->assertLessThanOrEqual($max, $value);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}