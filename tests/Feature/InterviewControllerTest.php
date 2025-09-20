<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InterviewControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $interview;
    protected $jobPosting;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->jobPosting = JobPosting::factory()->create(['user_id' => $this->user->id]);
        $this->interview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'job_posting_id' => $this->jobPosting->id
        ]);
    }

    public function test_user_can_view_interviews_index()
    {
        $response = $this->actingAs($this->user)
            ->get('/api/interviews');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'status',
                        'created_at',
                        'job_posting'
                    ]
                ]
            ]);
    }

    public function test_user_can_create_interview()
    {
        $interviewData = [
            'title' => 'Software Engineer Interview',
            'job_posting_id' => $this->jobPosting->id,
            'type' => 'technical',
            'difficulty' => 'medium'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/interviews', $interviewData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'status',
                    'type',
                    'difficulty'
                ]
            ]);

        $this->assertDatabaseHas('interviews', [
            'title' => 'Software Engineer Interview',
            'user_id' => $this->user->id,
            'job_posting_id' => $this->jobPosting->id
        ]);
    }

    public function test_user_can_view_specific_interview()
    {
        $response = $this->actingAs($this->user)
            ->get("/api/interviews/{$this->interview->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'status',
                    'questions',
                    'responses'
                ]
            ]);
    }

    public function test_user_cannot_view_other_users_interview()
    {
        $otherUser = User::factory()->create();
        $otherInterview = Interview::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->get("/api/interviews/{$otherInterview->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_interview()
    {
        $updateData = [
            'title' => 'Updated Interview Title',
            'status' => 'in_progress'
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/interviews/{$this->interview->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('interviews', [
            'id' => $this->interview->id,
            'title' => 'Updated Interview Title',
            'status' => 'in_progress'
        ]);
    }

    public function test_user_can_delete_interview()
    {
        $response = $this->actingAs($this->user)
            ->delete("/api/interviews/{$this->interview->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('interviews', [
            'id' => $this->interview->id
        ]);
    }

    public function test_user_can_start_interview()
    {
        // Create questions for the interview
        Question::factory()->count(3)->create([
            'interview_id' => $this->interview->id
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/interviews/{$this->interview->id}/start");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'interview',
                    'current_question',
                    'total_questions'
                ]
            ]);

        $this->assertDatabaseHas('interviews', [
            'id' => $this->interview->id,
            'status' => 'in_progress',
            'started_at' => now()
        ]);
    }

    public function test_user_can_submit_response()
    {
        Storage::fake('public');
        
        $question = Question::factory()->create([
            'interview_id' => $this->interview->id
        ]);

        $videoFile = UploadedFile::fake()->create('response.mp4', 1024, 'video/mp4');
        
        $responseData = [
            'question_id' => $question->id,
            'text_response' => 'This is my answer to the question.',
            'video_response' => $videoFile
        ];

        $response = $this->actingAs($this->user)
            ->postJson("/api/interviews/{$this->interview->id}/submit-response", $responseData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'text_response',
                    'video_path',
                    'ai_feedback'
                ]
            ]);

        $this->assertDatabaseHas('responses', [
            'question_id' => $question->id,
            'text_response' => 'This is my answer to the question.'
        ]);

        Storage::disk('public')->assertExists('responses/' . $videoFile->hashName());
    }

    public function test_user_can_complete_interview()
    {
        $this->interview->update(['status' => 'in_progress']);
        
        // Add some responses
        $question = Question::factory()->create(['interview_id' => $this->interview->id]);
        Response::factory()->create([
            'question_id' => $question->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/interviews/{$this->interview->id}/complete");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'interview',
                    'overall_score',
                    'feedback_summary'
                ]
            ]);

        $this->assertDatabaseHas('interviews', [
            'id' => $this->interview->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    public function test_user_can_get_next_question()
    {
        $questions = Question::factory()->count(3)->create([
            'interview_id' => $this->interview->id
        ]);

        // Answer first question
        Response::factory()->create([
            'question_id' => $questions[0]->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/interviews/{$this->interview->id}/next-question");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'question' => [
                        'id',
                        'question',
                        'category',
                        'difficulty'
                    ],
                    'progress' => [
                        'current',
                        'total',
                        'percentage'
                    ]
                ]
            ]);
    }

    public function test_user_can_get_interview_analytics()
    {
        // Create questions and responses with scores
        $questions = Question::factory()->count(3)->create([
            'interview_id' => $this->interview->id
        ]);

        foreach ($questions as $question) {
            Response::factory()->create([
                'question_id' => $question->id,
                'user_id' => $this->user->id,
                'ai_score' => rand(70, 95)
            ]);
        }

        $this->interview->update(['status' => 'completed']);

        $response = $this->actingAs($this->user)
            ->getJson("/api/interviews/{$this->interview->id}/analytics");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'overall_score',
                    'category_breakdown',
                    'strengths',
                    'areas_for_improvement',
                    'time_analysis'
                ]
            ]);
    }

    public function test_validation_errors_on_create_interview()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/interviews', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'type']);
    }

    public function test_unauthenticated_user_cannot_access_interviews()
    {
        $response = $this->getJson('/api/interviews');
        $response->assertStatus(401);
    }

    public function test_interview_generates_questions_on_creation()
    {
        $interviewData = [
            'title' => 'AI Generated Interview',
            'job_posting_id' => $this->jobPosting->id,
            'type' => 'behavioral',
            'difficulty' => 'medium'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/interviews', $interviewData);

        $response->assertStatus(201);
        
        $interview = Interview::latest()->first();
        $this->assertGreaterThan(0, $interview->questions()->count());
    }

    public function test_interview_recording_functionality()
    {
        Storage::fake('public');
        
        $question = Question::factory()->create([
            'interview_id' => $this->interview->id
        ]);

        $audioFile = UploadedFile::fake()->create('audio_response.wav', 2048, 'audio/wav');
        
        $responseData = [
            'question_id' => $question->id,
            'audio_response' => $audioFile,
            'recording_duration' => 120 // 2 minutes
        ];

        $response = $this->actingAs($this->user)
            ->postJson("/api/interviews/{$this->interview->id}/submit-response", $responseData);

        $response->assertStatus(201);
        
        Storage::disk('public')->assertExists('responses/' . $audioFile->hashName());
        
        $this->assertDatabaseHas('responses', [
            'question_id' => $question->id,
            'recording_duration' => 120
        ]);
    }
}