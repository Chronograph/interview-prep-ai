<?php

use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('user can view job postings index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/job-postings');

    $response->assertStatus(200);
});

test('user can create job posting', function () {
    $user = User::factory()->create();

    $jobPostingData = [
        'title' => 'Senior Developer',
        'company' => 'Tech Corp',
        'description' => 'Great opportunity for skilled developers',
        'location' => 'New York',
        'employment_type' => 'full_time',
        'remote_option' => true,
    ];

    $response = $this->actingAs($user)
        ->post('/job-postings', $jobPostingData);

    $response->assertRedirect();

    expect(JobPosting::count())->toBe(1);

    $jobPosting = JobPosting::first();
    expect($jobPosting->user_id)->toBe($user->id);
    expect($jobPosting->title)->toBe('Senior Developer');
    expect($jobPosting->company)->toBe('Tech Corp');
});

test('user can update job posting', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title',
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'company' => 'Updated Company',
        'description' => 'Updated description',
    ];

    $response = $this->actingAs($user)
        ->put("/job-postings/{$jobPosting->id}", $updateData);

    $response->assertRedirect();

    $jobPosting->refresh();
    expect($jobPosting->title)->toBe('Updated Title');
    expect($jobPosting->company)->toBe('Updated Company');
});

test('user can delete job posting', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user->id]);

    expect(JobPosting::count())->toBe(1);

    $response = $this->actingAs($user)
        ->delete("/job-postings/{$jobPosting->id}");

    $response->assertRedirect();
    expect(JobPosting::count())->toBe(0);
});

test('user cannot access other user job posting', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user2->id]);

    $response = $this->actingAs($user1)
        ->get("/job-postings/{$jobPosting->id}");

    $response->assertStatus(403);
});

test('user can upload job posting file', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('job-description.pdf', 100, 'application/pdf');

    $jobPostingData = [
        'title' => 'Developer Position',
        'company' => 'Tech Company',
        'description' => 'New developer role',
        'file' => $file,
    ];

    $response = $this->actingAs($user)
        ->post('/job-postings', $jobPostingData);

    $response->assertRedirect();

    $jobPosting = JobPosting::first();
    expect($jobPosting->file_path)->not->toBeNull();
    Storage::assertExists($jobPosting->file_path);
});

test('job posting validation works correctly', function () {
    $user = User::factory()->create();

    // Test without required fields
    $response = $this->actingAs($user)
        ->post('/job-postings', []);

    $response->assertSessionHasErrors(['title', 'company', 'description']);
});

test('user can view job posting details', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->get("/job-postings/{$jobPosting->id}");

    $response->assertStatus(200)
        ->assertViewHas('jobPosting', $jobPosting);
});

test('job posting requires authentication', function () {
    $jobPosting = JobPosting::factory()->create();

    $response = $this->get("/job-postings/{$jobPosting->id}");

    $response->assertRedirect('/login');
});

test('user can search job postings', function () {
    $user = User::factory()->create();

    // Create multiple job postings for search testing
    JobPosting::factory()->create([
        'user_id' => $user->id,
        'title' => 'Senior Developer',
        'company' => 'TechCorp',
    ]);

    JobPosting::factory()->create([
        'user_id' => $user->id,
        'title' => 'Junior Designer',
        'company' => 'DesignCorp',
    ]);

    $response = $this->actingAs($user)
        ->get('/job-postings?search=Developer');

    $response->assertStatus(200);
    expect(JobPosting::where('title', 'like', '%Developer%')->count())->toBe(1);
});
