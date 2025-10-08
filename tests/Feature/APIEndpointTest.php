<?php

use App\Models\Interview;
use App\Models\InterviewSession;
use App\Models\JobPosting;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('api returns interview data for authenticated user', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user->id]);
    $interview = Interview::factory()->create([
        'user_id' => $user->id,
        'job_posting_id' => $jobPosting->id,
    ]);

    $response = $this->actingAs($user, 'api')
        ->getJson('/api/interviews');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'status',
                    'created_at',
                    'job_posting',
                ],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($interview->id);
});

test('api requires authentication for protected endpoints', function () {
    $response = $this->getJson('/api/interviews');
    $response->assertStatus(401);

    $response = $this->getJson('/api/job-postings');
    $response->assertStatus(401);
});

test('api can create interview session', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user->id]);
    $interview = Interview::factory()->create([
        'user_id' => $user->id,
        'job_posting_id' => $jobPosting->id,
    ]);

    $sessionData = [
        'interview_id' => $interview->id,
        'session_config' => [
            'duration_minutes' => 60,
            'question_count' => 10,
        ],
    ];

    $response = $this->actingAs($user, 'api')
        ->postJson('/api/interview-sessions', $sessionData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'interview_id',
                'status',
            ],
        ]);

    expect(InterviewSession::count())->toBe(1);
});

test('api returns dashboard statistics', function () {
    $user = User::factory()->create();

    // Create test data
    $interviews = Interview::factory()->count(3)->create(['user_id' => $user->id]);
    InterviewSession::factory()->count(2)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'api')
        ->getJson('/api/dashboard/stats');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'stats' => [
                'total_interviews',
                'completed_interviews',
                'pending_interviews',
                'average_score',
            ],
        ]);
});

test('api can create job posting', function () {
    $user = User::factory()->create();

    $jobPostingData = [
        'title' => 'Senior Developer',
        'company' => 'Tech Corp',
        'description' => 'Great opportunity',
        'location' => 'Remote',
        'employment_type' => 'full_time',
    ];

    $response = $this->actingAs($user, 'api')
        ->postJson('/api/job-postings', $jobPostingData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'company',
                'user_id',
            ],
        ]);

    expect(JobPosting::count())->toBe(1);
    expect(JobPosting::first()->user_id)->toBe($user->id);
});

test('api can update interview session', function () {
    $user = User::factory()->create();
    $session = InterviewSession::factory()->create(['user_id' => $user->id]);

    $updateData = [
        'status' => 'completed',
        'overall_score' => 85,
        'feedback' => 'Good performance',
    ];

    $response = $this->actingAs($user, 'api')
        ->putJson("/api/interview-sessions/{$session->id}", $updateData);

    $response->assertStatus(200);

    $session->refresh();
    expect($session->status)->toBe('completed');
    expect($session->overall_score)->toBe(85);
});

test('api filters interviews by status', function () {
    $user = User::factory()->create();

    Interview::factory()->create(['user_id' => $user->id, 'status' => 'completed']);
    Interview::factory()->create(['user_id' => $user->id, 'status' => 'in_progress']);

    $response = $this->actingAs($user, 'api')
        ->getJson('/api/interviews?status=completed');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.status'))->toBe('completed');
});

test('api pagination works correctly', function () {
    $user = User::factory()->create();
    Interview::factory()->count(15)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'api')
        ->getJson('/api/interviews?per_page=5');

    $response->assertStatus(200);

    $data = $response->json('data');
    $meta = $response->json('meta');

    expect($data)->toHaveCount(5);
    expect($meta['per_page'])->toBe(5);
    expect($meta['total'])->toBeGreaterThan(10);
});

test('api returns interview feedback', function () {
    $user = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user->id]);
    InterviewSession::factory()->create([
        'user_id' => $user->id,
        'interview_id' => $interview->id,
    ]);

    $response = $this->actingAs($user, 'api')
        ->getJson("/api/interviews/{$interview->id}/feedback");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'interview_id',
                'overall_score',
                'detailed_feedback',
                'strengths',
                'areas_for_improvement',
            ],
        ]);
});

test('api validation works for invalid data', function () {
    $user = User::factory()->create();

    // Test with missing required fields
    $response = $this->actingAs($user, 'api')
        ->postJson('/api/job-postings', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'company', 'description']);

    // Test with invalid employment type
    $response = $this->actingAs($user, 'api')
        ->postJson('/api/job-postings', [
            'title' => 'Developer',
            'company' => 'Test Corp',
            'description' => 'Test job',
            'employment_type' => 'invalid_type',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['employment_type']);
});

test('api handles resume file upload', function () {
    $user = User::factory()->create();

    Storage::fake('local');

    $file = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');

    $response = $this->actingAs($user, 'api')
        ->postJson('/api/resumes', [
            'title' => 'My Resume',
            'summary' => 'Software Developer',
            'file' => $file,
        ]);

    $response->assertStatus(201);

    $resume = Resume::first();
    expect($resume->file_path)->not->toBeNull();
    expect($resume->file_type)->toBe('application/pdf');
});
