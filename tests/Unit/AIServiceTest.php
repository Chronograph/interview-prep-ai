<?php

use App\Models\JobPosting;
use App\Models\User;
use App\Services\AIFeedbackService;
use App\Services\AIInterviewService;
use App\Services\AIResumeParserService;
use App\Services\AIService;
use Illuminate\Support\Facades\Http;

describe('AI Services', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->jobPosting = JobPosting::factory()->create(['user_id' => $this->user->id]);
    });

    test('AI interview service can generate questions', function () {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                [
                                    'question' => 'Tell me about yourself',
                                    'type' => 'behavioral',
                                    'difficulty' => 'medium',
                                ],
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $interviewService = new AIInterviewService;
        $questions = $interviewService->generateQuestions(
            $this->jobPosting,
            'behavioral',
            'medium',
            5
        );

        expect($questions)->toBeArray();
        expect(count($questions))->toBe(1);
        expect($questions[0]['question'])->toBe('Tell me about yourself');
    });

    test('AI feedback service can analyze interview responses', function () {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'score' => 85,
                                'feedback' => 'Strong technical knowledge demonstrated',
                                'improvement_areas' => ['communication', 'examples'],
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $feedbackService = new AIFeedbackService;
        $feedback = $feedbackService->analyzeResponse(
            'What is object-oriented programming?',
            'Object-oriented programming is a programming paradigm...',
            ['technical_knowledge', 'programming_concepts']
        );

        expect($feedback)->toBeArray();
        expect($feedback['score'])->toBe(85);
        expect($feedback['feedback'])->toBe('Strong technical knowledge demonstrated');
    });

    test('AI resume parser can extract key information', function () {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'skills' => ['PHP', 'Laravel', 'JavaScript'],
                                'experience' => [
                                    [
                                        'company' => 'ABC Corp',
                                        'position' => 'Senior Developer',
                                        'duration' => '2020-2023',
                                    ],
                                ],
                                'education' => [
                                    [
                                        'degree' => 'Computer Science',
                                        'institution' => 'University',
                                    ],
                                ],
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $parserService = new AIResumeParserService;
        $parsed = $parserService->parseResumeContent('Mock resume content here...');

        expect($parsed)->toBeArray();
        expect($parsed['skills'])->toContain('PHP');
        expect($parsed['experience'])->toBeArray();
        expect($parsed['education'])->toBeArray();
    });

    test('AI service handles API failure gracefully', function () {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response('', 500),
        ]);

        $aiService = new AIService;

        expect(function () use ($aiService) {
            $aiService->generateCompletion('Test prompt');
        })->toThrow(Exception::class);
    });

    test('AI interview service with custom parameters', function () {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                [
                                    'question' => 'Describe your biggest challenge',
                                    'type' => 'behavioral',
                                    'difficulty' => 'hard',
                                ],
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $interviewService = new AIInterviewService;
        $questions = $interviewService->generateQuestions(
            $this->jobPosting,
            'behavioral',
            'hard',
            10,
            ['leadership', 'problem_solving']
        );

        expect($questions)->toBeArray();
        // Verify that custom focus areas are included in API calls
        Http::assertSent(function ($request) {
            return str_contains($request->body(), 'leadership');
        });
    });

    test('AI feedback service with scoring criteria', function () {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'score' => 92,
                                'feedback' => 'Excellent technical response',
                                'strengths' => ['clarity', 'depth'],
                                'improvement_areas' => [],
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $feedbackService = new AIFeedbackService;
        $feedback = $feedbackService->analyzeResponse(
            'Explain microservices architecture',
            'Microservices is an architectural style...',
            ['technical_knowledge', 'architecture'],
            ['clarity', 'technical_accuracy', 'completeness']
        );

        expect($feedback)->toBeArray();
        expect($feedback['score'])->toBeGreaterThan(90);
    });

    test('AI service validates interview questions structure', function () {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                [
                                    'question' => 'Test question',
                                    'type' => 'behavioral',
                                    'difficulty' => 'medium',
                                    'category' => 'leadership',
                                ],
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $interviewService = new AIInterviewService;
        $questions = $interviewService->generateQuestions($this->jobPosting);

        expect($questions)->toBeArray();
        expect($questions[0])->toHaveKey('question');
        expect($questions[0])->toHaveKey('type');
        expect($questions[0])->toHaveKey('difficulty');
    });
});
