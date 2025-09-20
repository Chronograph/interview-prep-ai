<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_dashboard_stats()
    {
        // Create test data
        $this->createTestInterviews();

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => [
                    'total_interviews',
                    'completed_interviews',
                    'pending_interviews',
                    'average_score',
                    'total_questions_answered',
                    'improvement_rate'
                ],
                'recent_interviews' => [
                    '*' => [
                        'id',
                        'title',
                        'status',
                        'created_at',
                        'score',
                        'questions_count'
                    ]
                ],
                'progress_data' => [
                    '*' => [
                        'date',
                        'score',
                        'interviews_count'
                    ]
                ]
            ]);
    }

    public function test_dashboard_stats_calculation_accuracy()
    {
        // Create specific test data
        $completedInterview1 = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'completed_at' => now()->subDays(5)
        ]);
        
        $completedInterview2 = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'completed_at' => now()->subDays(2)
        ]);
        
        $pendingInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        // Create questions and responses with known scores
        $this->createQuestionsAndResponses($completedInterview1, [85, 90, 78]);
        $this->createQuestionsAndResponses($completedInterview2, [92, 88, 95]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
        
        $data = $response->json();
        
        $this->assertEquals(3, $data['stats']['total_interviews']);
        $this->assertEquals(2, $data['stats']['completed_interviews']);
        $this->assertEquals(1, $data['stats']['pending_interviews']);
        $this->assertEquals(6, $data['stats']['total_questions_answered']);
        
        // Average score should be (85+90+78+92+88+95)/6 = 88
        $this->assertEquals(88, $data['stats']['average_score']);
    }

    public function test_recent_interviews_are_properly_ordered()
    {
        // Create interviews with different dates
        $oldInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => now()->subDays(10)
        ]);
        
        $recentInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => now()->subDays(1)
        ]);
        
        $newestInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => now()
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
        
        $recentInterviews = $response->json('recent_interviews');
        
        // Should be ordered by most recent first
        $this->assertEquals($newestInterview->id, $recentInterviews[0]['id']);
        $this->assertEquals($recentInterview->id, $recentInterviews[1]['id']);
        $this->assertEquals($oldInterview->id, $recentInterviews[2]['id']);
    }

    public function test_progress_data_tracks_improvement_over_time()
    {
        // Create interviews over different time periods with varying scores
        $this->createInterviewWithScore(now()->subDays(30), [70, 75, 80]); // Average: 75
        $this->createInterviewWithScore(now()->subDays(20), [80, 85, 90]); // Average: 85
        $this->createInterviewWithScore(now()->subDays(10), [85, 90, 95]); // Average: 90

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
        
        $progressData = $response->json('progress_data');
        
        // Should show improvement over time
        $this->assertGreaterThan(0, count($progressData));
        
        // Check that improvement rate is calculated
        $stats = $response->json('stats');
        $this->assertGreaterThan(0, $stats['improvement_rate']);
    }

    public function test_user_can_view_recent_activity()
    {
        $this->createTestInterviews();

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/recent-activity');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'title',
                        'description',
                        'created_at',
                        'metadata'
                    ]
                ]
            ]);
    }

    public function test_user_can_view_performance_analytics()
    {
        $this->createTestInterviews();

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/analytics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'performance_trends',
                    'category_breakdown',
                    'difficulty_analysis',
                    'time_analysis',
                    'strengths',
                    'areas_for_improvement'
                ]
            ]);
    }

    public function test_user_can_view_progress_tracking()
    {
        $this->createTestInterviews();

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/progress');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'weekly_progress',
                    'monthly_progress',
                    'skill_development',
                    'goals' => [
                        '*' => [
                            'id',
                            'title',
                            'target',
                            'current',
                            'progress_percentage'
                        ]
                    ]
                ]
            ]);
    }

    public function test_dashboard_handles_empty_data_gracefully()
    {
        // User with no interviews
        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
        
        $data = $response->json();
        
        $this->assertEquals(0, $data['stats']['total_interviews']);
        $this->assertEquals(0, $data['stats']['completed_interviews']);
        $this->assertEquals(0, $data['stats']['average_score']);
        $this->assertEmpty($data['recent_interviews']);
    }

    public function test_dashboard_filters_user_specific_data()
    {
        // Create data for current user
        $userInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);
        
        // Create data for another user
        $otherUser = User::factory()->create();
        $otherInterview = Interview::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'completed'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
        
        $data = $response->json();
        
        // Should only show current user's data
        $this->assertEquals(1, $data['stats']['total_interviews']);
        $this->assertCount(1, $data['recent_interviews']);
        $this->assertEquals($userInterview->id, $data['recent_interviews'][0]['id']);
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->getJson('/api/dashboard/stats');
        $response->assertStatus(401);
    }

    public function test_dashboard_performance_with_large_dataset()
    {
        // Create a large number of interviews to test performance
        Interview::factory()->count(50)->create([
            'user_id' => $this->user->id
        ]);

        $startTime = microtime(true);
        
        $response = $this->actingAs($this->user)
            ->getJson('/api/dashboard/stats');

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // Should complete within reasonable time (2 seconds)
        $this->assertLessThan(2.0, $executionTime);
    }

    protected function createTestInterviews()
    {
        // Create completed interviews
        $completedInterviews = Interview::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'completed_at' => now()->subDays(rand(1, 10))
        ]);

        // Create pending interviews
        Interview::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        // Add questions and responses to completed interviews
        foreach ($completedInterviews as $interview) {
            $this->createQuestionsAndResponses($interview, [rand(70, 100), rand(70, 100), rand(70, 100)]);
        }
    }

    protected function createQuestionsAndResponses($interview, $scores)
    {
        foreach ($scores as $score) {
            $question = Question::factory()->create([
                'interview_id' => $interview->id
            ]);
            
            Response::factory()->create([
                'question_id' => $question->id,
                'user_id' => $this->user->id,
                'ai_score' => $score
            ]);
        }
    }

    protected function createInterviewWithScore($date, $scores)
    {
        $interview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'created_at' => $date,
            'completed_at' => $date->addHours(1)
        ]);

        $this->createQuestionsAndResponses($interview, $scores);
        
        return $interview;
    }
}