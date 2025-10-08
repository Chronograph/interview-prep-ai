<?php

use App\Models\Interview;
use App\Models\InterviewSession;
use App\Models\JobPosting;
use App\Models\Resume;
use App\Models\User;

it('can create a user', function () {
    $user = User::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    expect($user->name)->toBe('Jane Doe');
    expect($user->email)->toBe('jane@example.com');
});

it('has job postings relationship', function () {
    $user = User::factory()->create();
    $jobPosting = JobPosting::factory()->create(['user_id' => $user->id]);

    expect($user->jobPostings)->toHaveCount(1);
    expect($user->jobPostings->first()->id)->toBe($jobPosting->id);
});

it('has interviews relationship', function () {
    $user = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user->id]);

    expect($user->interviews)->toHaveCount(1);
    expect($user->interviews->first()->id)->toBe($interview->id);
});

it('has resumes relationship', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user->id]);

    expect($user->resumes)->toHaveCount(1);
    expect($user->resumes->first()->id)->toBe($resume->id);
});

it('has interview sessions relationship', function () {
    $user = User::factory()->create();
    $session = InterviewSession::factory()->create(['user_id' => $user->id]);

    expect($user->interviewSessions)->toHaveCount(1);
    expect($user->interviewSessions->first()->id)->toBe($session->id);
});
