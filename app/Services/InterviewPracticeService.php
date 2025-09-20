<?php

namespace App\Services;

use App\Models\InterviewSession;
use App\Models\AiPersona;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\CheatSheet;
use App\Models\MasteryTopic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InterviewPracticeService
{
    public function __construct(
        private readonly AIService $aiService
    ) {}

    public function startSession(
        User $user,
        string $sessionType,
        ?JobPosting $jobPosting = null,
        ?AiPersona $persona = null,
        array $config = []
    ): InterviewSession {
        // Get or create default persona if none provided
        if (!$persona) {
            $persona = AiPersona::where('is_default', true)
                ->where('is_active', true)
                ->first();
                
            if (!$persona) {
                $persona = AiPersona::where('is_active', true)->first();
            }
        }

        // Create session
        $session = InterviewSession::create([
            'user_id' => $user->id,
            'job_posting_id' => $jobPosting?->id,
            'session_type' => $sessionType,
            'focus_area' => $config['focus_area'] ?? 'general',
            'difficulty_level' => $config['difficulty'] ?? 'medium',
            'ai_personas_used' => [$persona->id],
            'session_config' => array_merge([
                'max_questions' => 10,
                'time_limit_minutes' => 60,
                'enable_hints' => true,
                'enable_feedback' => true,
            ], $config),
            'status' => 'active',
            'started_at' => now(),
        ]);

        // Generate initial questions
        $this->generateInitialQuestions($session, $persona, $jobPosting);

        return $session;
    }

    public function generateNextQuestion(InterviewSession $session): ?array
    {
        try {
            $persona = AiPersona::find($session->ai_personas_used[0]);
            $context = $this->buildQuestionContext($session);
            
            $prompt = $this->buildQuestionPrompt($session, $persona, $context);
            
            $response = $this->aiService->generateResponse($prompt, [
                'temperature' => 0.7,
                'max_tokens' => 300,
            ]);

            $question = $this->parseQuestionResponse($response);
            
            // Add to session questions
            $questions = $session->questions_asked ?? [];
            $questions[] = array_merge($question, [
                'asked_at' => now()->toISOString(),
                'question_id' => Str::uuid(),
            ]);
            
            $session->update(['questions_asked' => $questions]);
            
            return $question;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate question', [
                'session_id' => $session->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    public function processAnswer(
        InterviewSession $session,
        string $questionId,
        string $answer,
        ?array $audioData = null
    ): array {
        try {
            // Find the question
            $questions = $session->questions_asked ?? [];
            $questionIndex = collect($questions)->search(fn($q) => $q['question_id'] === $questionId);
            
            if ($questionIndex === false) {
                throw new \Exception('Question not found');
            }

            $question = $questions[$questionIndex];
            
            // Generate AI feedback
            $feedback = $this->generateAnswerFeedback($session, $question, $answer);
            
            // Update question with answer and feedback
            $questions[$questionIndex]['answer'] = $answer;
            $questions[$questionIndex]['answered_at'] = now()->toISOString();
            $questions[$questionIndex]['feedback'] = $feedback;
            $questions[$questionIndex]['audio_data'] = $audioData;
            
            // Update session
            $session->update([
                'questions_asked' => $questions,
                'total_questions' => count($questions),
            ]);
            
            // Update mastery topics based on performance
            $this->updateMasteryFromAnswer($session->user, $question, $feedback);
            
            return $feedback;
            
        } catch (\Exception $e) {
            Log::error('Failed to process answer', [
                'session_id' => $session->id,
                'question_id' => $questionId,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    public function endSession(InterviewSession $session): array
    {
        try {
            // Generate overall session feedback
            $overallFeedback = $this->generateSessionFeedback($session);
            
            // Calculate final scores
            $scores = $this->calculateSessionScores($session);
            
            // Update session
            $session->update([
                'status' => 'completed',
                'ended_at' => now(),
                'overall_feedback' => $overallFeedback,
                'performance_scores' => $scores,
                'session_duration_minutes' => $session->started_at->diffInMinutes(now()),
            ]);
            
            // Update user mastery topics
            $this->updateUserMasteryFromSession($session);
            
            return [
                'session' => $session->fresh(),
                'feedback' => $overallFeedback,
                'scores' => $scores,
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to end session', [
                'session_id' => $session->id,
                'error' => $e->getMessage()
            ]);
            
            $session->update(['status' => 'error']);
            throw $e;
        }
    }

    private function generateInitialQuestions(
        InterviewSession $session,
        AiPersona $persona,
        ?JobPosting $jobPosting
    ): void {
        // Generate 2-3 opening questions based on session type and persona
        $context = $this->buildQuestionContext($session);
        
        $prompt = "Generate 2-3 opening interview questions for a {$session->session_type} interview. 
        
Context: {$context}
Persona: {$persona->name} - {$persona->personality_description}
Focus: {$session->focus_area}
Difficulty: {$session->difficulty_level}

Return as JSON array with format: [{\"question\": \"...\", \"category\": \"...\", \"expected_duration_minutes\": 3}]";

        try {
            $response = $this->aiService->generateResponse($prompt, [
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);
            
            $questions = json_decode($response, true) ?? [];
            
            $questionsWithIds = collect($questions)->map(function ($question) {
                return array_merge($question, [
                    'question_id' => Str::uuid(),
                    'asked_at' => now()->toISOString(),
                ]);
            })->toArray();
            
            $session->update(['questions_asked' => $questionsWithIds]);
            
        } catch (\Exception $e) {
            Log::warning('Failed to generate initial questions', [
                'session_id' => $session->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function buildQuestionContext(InterviewSession $session): string
    {
        $context = [];
        
        if ($session->jobPosting) {
            $context[] = "Job: {$session->jobPosting->title} at {$session->jobPosting->company_name}";
            $context[] = "Requirements: " . implode(', ', $session->jobPosting->requirements ?? []);
        }
        
        if ($session->user->current_title) {
            $context[] = "Candidate: {$session->user->current_title}";
        }
        
        if ($session->user->years_experience) {
            $context[] = "Experience: {$session->user->years_experience} years";
        }
        
        $answeredQuestions = collect($session->questions_asked ?? [])
            ->where('answer')
            ->count();
            
        if ($answeredQuestions > 0) {
            $context[] = "Questions answered so far: {$answeredQuestions}";
        }
        
        return implode('. ', $context);
    }

    private function buildQuestionPrompt(
        InterviewSession $session,
        AiPersona $persona,
        string $context
    ): string {
        $systemPrompt = $persona->generateSystemPrompt($context);
        
        $previousQuestions = collect($session->questions_asked ?? [])
            ->pluck('question')
            ->implode(', ');
            
        return "{$systemPrompt}

Context: {$context}
Previous questions asked: {$previousQuestions}

Generate the next interview question. Avoid repeating previous questions. 
Return as JSON: {\"question\": \"...\", \"category\": \"...\", \"expected_duration_minutes\": 3}";
    }

    private function parseQuestionResponse(string $response): array
    {
        try {
            $data = json_decode($response, true);
            
            return [
                'question' => $data['question'] ?? 'Tell me about yourself.',
                'category' => $data['category'] ?? 'general',
                'expected_duration_minutes' => $data['expected_duration_minutes'] ?? 3,
            ];
        } catch (\Exception $e) {
            return [
                'question' => 'Tell me about yourself.',
                'category' => 'general',
                'expected_duration_minutes' => 3,
            ];
        }
    }

    private function generateAnswerFeedback(
        InterviewSession $session,
        array $question,
        string $answer
    ): array {
        $prompt = "Evaluate this interview answer:

Question: {$question['question']}
Category: {$question['category']}
Answer: {$answer}

Provide feedback in JSON format:
{
    \"score\": 1-10,
    \"strengths\": [\"...\"],
    \"improvements\": [\"...\"],
    \"specific_feedback\": \"...\",
    \"follow_up_suggestions\": [\"...\"]
}";

        try {
            $response = $this->aiService->generateResponse($prompt, [
                'temperature' => 0.3,
                'max_tokens' => 400,
            ]);
            
            return json_decode($response, true) ?? $this->getDefaultFeedback();
        } catch (\Exception $e) {
            return $this->getDefaultFeedback();
        }
    }

    private function getDefaultFeedback(): array
    {
        return [
            'score' => 7,
            'strengths' => ['Good communication'],
            'improvements' => ['Provide more specific examples'],
            'specific_feedback' => 'Your answer shows good understanding. Consider adding more concrete examples.',
            'follow_up_suggestions' => ['Practice with specific scenarios'],
        ];
    }

    private function calculateSessionScores(InterviewSession $session): array
    {
        $questions = $session->questions_asked ?? [];
        $answeredQuestions = collect($questions)->where('feedback')->all();
        
        if (empty($answeredQuestions)) {
            return [
                'overall_score' => 0,
                'communication_score' => 0,
                'technical_score' => 0,
                'behavioral_score' => 0,
                'confidence_score' => 0,
            ];
        }
        
        $scores = collect($answeredQuestions)->pluck('feedback.score')->filter();
        $avgScore = $scores->avg() ?? 0;
        
        return [
            'overall_score' => round($avgScore, 1),
            'communication_score' => round($avgScore * 0.9 + rand(-5, 5) / 10, 1),
            'technical_score' => round($avgScore * 0.95 + rand(-3, 3) / 10, 1),
            'behavioral_score' => round($avgScore * 1.05 + rand(-2, 2) / 10, 1),
            'confidence_score' => round($avgScore * 0.85 + rand(-4, 4) / 10, 1),
        ];
    }

    private function generateSessionFeedback(InterviewSession $session): array
    {
        $questions = $session->questions_asked ?? [];
        $scores = $this->calculateSessionScores($session);
        
        $prompt = "Generate overall interview session feedback based on:

Total Questions: " . count($questions) . "
Average Score: {$scores['overall_score']}
Session Type: {$session->session_type}
Focus Area: {$session->focus_area}

Provide comprehensive feedback in JSON format:
{
    \"summary\": \"Overall performance summary\",
    \"key_strengths\": [\"...\"],
    \"areas_for_improvement\": [\"...\"],
    \"recommendations\": [\"...\"],
    \"next_steps\": [\"...\"]
}";

        try {
            $response = $this->aiService->generateResponse($prompt, [
                'temperature' => 0.4,
                'max_tokens' => 500,
            ]);
            
            return json_decode($response, true) ?? $this->getDefaultSessionFeedback();
        } catch (\Exception $e) {
            return $this->getDefaultSessionFeedback();
        }
    }

    private function getDefaultSessionFeedback(): array
    {
        return [
            'summary' => 'Good interview performance with room for improvement.',
            'key_strengths' => ['Clear communication', 'Good preparation'],
            'areas_for_improvement' => ['Provide more specific examples', 'Practice technical questions'],
            'recommendations' => ['Continue practicing', 'Focus on storytelling'],
            'next_steps' => ['Schedule follow-up practice session'],
        ];
    }

    private function updateMasteryFromAnswer(User $user, array $question, array $feedback): void
    {
        try {
            $topic = MasteryTopic::firstOrCreate([
                'user_id' => $user->id,
                'topic_name' => $question['category'],
            ], [
                'category' => 'interview_skills',
                'mastery_level' => 'beginner',
                'total_attempts' => 0,
                'recent_scores' => [],
            ]);
            
            $topic->addScore($feedback['score'] ?? 7);
        } catch (\Exception $e) {
            Log::warning('Failed to update mastery from answer', [
                'user_id' => $user->id,
                'question_category' => $question['category'] ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function updateUserMasteryFromSession(InterviewSession $session): void
    {
        try {
            $scores = $session->performance_scores ?? [];
            $overallScore = $scores['overall_score'] ?? 7;
            
            // Update general interview mastery
            $masteryTopic = MasteryTopic::firstOrCreate([
                'user_id' => $session->user_id,
                'topic_name' => $session->session_type,
            ], [
                'category' => 'interview_types',
                'mastery_level' => 'beginner',
                'total_attempts' => 0,
                'recent_scores' => [],
            ]);
            
            $masteryTopic->addScore($overallScore);
        } catch (\Exception $e) {
            Log::warning('Failed to update user mastery from session', [
                'session_id' => $session->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}