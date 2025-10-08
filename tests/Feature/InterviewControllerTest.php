<?php

use App\Models\Interview;
use App\Models\InterviewSession;
use App\Models\JobPosting;
use App\Models\User;

test('user can view interviews index page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/interviews');

    $response->assertStatus(200);
});

test('user can create new interview', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user->id]);

    $interviewData = [
        'job_posting_id' => $jobPosting->id,
        'title' => 'Mock Interview',
        'description' => 'Test interview',
        'interview_type' => 'behavioral',
        'duration_minutes' => 45,
    ];

    $response = $this->actingAs($user)
        ->post('/interviews', $interviewData);

    $response->assertRedirect();

    expect(Interview::count())->toBe(1);

    $interview = Interview::first();
    expect($interview->user_id)->toBe($user->id);
    expect($interview->job_posting_id)->toBe($jobPosting->id);
    expect($interview->title)->toBe('Mock Interview');
});

test('user can update interview', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user->id]);
    $interview = Interview::factory()->create([
        'user_id' => $user->id,
        'job_posting_id' => $jobPosting->id,
        'title' => 'Original Title',
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'description' => 'Updated description',
        'status' => 'completed',
    ];

    $response = $this->actingAs($user)
        ->put("/interviews/{$interview->id}", $updateData);

    $response->assertRedirect();

    $interview->refresh();
    expect($interview->title)->toBe('Updated Title');
    expect($interview->description)->toBe('Updated description');
    expect($interview->status)->toBe('completed');
});

test('user can delete interview', function () {
    $user = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user->id]);

    expect(Interview::count())->toBe(1);

    $response = $this->actingAs($user)
        ->delete("/interviews/{$interview->id}");

    $response->assertRedirect();
    expect(Interview::count())->toBe(0);
});

test('user cannot access other user interview', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user2->id]);

    $response = $this->actingAs($user1)
        ->get("/interviews/{$interview->id}");

    $response->assertStatus(403);
});

test('user can view their interview details', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user->id]);
    $interview = Interview::factory()->create([
        'user_id' => $user->id,
        'job_posting_id' => $jobPosting->id,
    ]);

    $response = $this->actingAs($user)
        ->get("/interviews/{$interview->id}");

    $response->assertStatus(200)
        ->assertViewHas('interview', $interview);
});

test('interview requires authentication', function () {
    $interview = Interview::factory()->create();

    $response = $this->get("/interviews/{$interview->id}");

    $response->assertRedirect('/login');
});

test('interview creation requires job posting ownership', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user2->id]);

    $interviewData = [
        'job_posting_id' => $jobPosting->id,
        'title' => 'Unauthorized Interview',
    ];

    $response = $this->actingAs($user1)
        ->post('/interviews', $interviewData);

    $response->assertSessionHasErrors();
});

test('user can start interview session', function () {
    $user = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->post("/interviews/{$interview->id}/start");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'session_id',
            'questions',
            'interview' => [
                'id',
                'title',
                'status',
            ],
        ]);

    $interview->refresh();
    expect($interview->status)->toBe('in_progress');
});

test('user can complete interview session', function () {
    $user = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user->id]);
    $session = InterviewSession::factory()->create([
        'user_id' => $user->id,
        'interview_id' => $interview->id,
        'status' => 'in_progress',
    ]);

    $response = $this->actingAs($user)
        ->post("/interviews/{$interview->id}/complete", [
            'session_id' => $session->id,
            'overall_score' => 85,
        ]);

    $response->assertJsonStructure([
        'result',
        'feedback',
    ]);

    $interview->refresh();
    expect($interview->status)->toBe('completed');
    expect($interview->overall_score)->toBe(85);
});
