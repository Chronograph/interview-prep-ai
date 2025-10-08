<?php

use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Resume;
use App\Models\User;

describe('Model Relationships', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('user has many job postings', function () {
        $jobPosting1 = JobPosting::factory()->create(['user_id' => $this->user->id]);
        $jobPosting2 = JobPosting::factory()->create(['user_id' => $this->user->id]);

        expect($this->user->jobPostings)->toHaveCount(2);
        expect($this->user->jobPostings->contains($jobPosting1))->toBeTrue();
        expect($this->user->jobPostings->contains($jobPosting2))->toBeTrue();
    });

    it('user has many interviews', function () {
        $interview1 = Interview::factory()->create(['user_id' => $this->user->id]);
        $interview2 = Interview::factory()->create(['user_id' => $this->user->id]);

        expect($this->user->interviews)->toHaveCount(2);
        expect($this->user->interviews->contains($interview1))->toBeTrue();
        expect($this->user->interviews->contains($interview2))->toBeTrue();
    });

    it('interview belongs to user', function () {
        $interview = Interview::factory()->create(['user_id' => $this->user->id]);

        expect($interview->user->id)->toBe($this->user->id);
    });

    it('interview belongs to job posting', function () {
        $jobPosting = JobPosting::factory()->create(['user_id' => $this->user->id]);
        $interview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'job_posting_id' => $jobPosting->id,
        ]);

        expect($interview->jobPosting->id)->toBe($jobPosting->id);
    });

    it('resume belongs to user', function () {
        $resume = Resume::factory()->create(['user_id' => $this->user->id]);

        expect($resume->user->id)->toBe($this->user->id);
    });
});

describe('Model Scopes', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('interview completed scope works', function () {
        $completedInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
        ]);

        $inProgressInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $completedInterviews = Interview::completed()->get();

        expect($completedInterviews)->toHaveCount(1);
        expect($completedInterviews->first()->id)->toBe($completedInterview->id);
    });

    it('interview in progress scope works', function () {
        $completedInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
        ]);

        $inProgressInterview = Interview::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $inProgressInterviews = Interview::inProgress()->get();

        expect($inProgressInterviews)->toHaveCount(1);
        expect($inProgressInterviews->first()->id)->toBe($inProgressInterview->id);
    });
});

describe('Model Attributes and Accessors', function () {
    it('interview actual duration calculates correctly', function () {
        $user = User::factory()->create();
        $now = now();
        $startTime = $now->copy()->subMinutes(30);

        $interview = Interview::factory()->create([
            'user_id' => $user->id,
            'started_at' => $startTime,
            'completed_at' => $now,
        ]);

        expect($interview->actual_duration)->toBe(30);
    });

    it('interview is completed attribute works', function () {
        $user = User::factory()->create();

        $completedInterview = Interview::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        $inProgressInterview = Interview::factory()->create([
            'user_id' => $user->id,
            'status' => 'in_progress',
        ]);

        expect($completedInterview->is_completed)->toBeTrue();
        expect($inProgressInterview->is_completed)->toBeFalse();
    });
});

describe('Model Fillable Attributes', function () {
    it('user has required fillable attributes', function () {
        $fillable = [
            'name',
            'email',
            'password',
        ];

        $user = new User;
        expect($user->getFillable())->toBeArray();
        expect($user->getFillable())->toContain('name', 'email', 'password');
    });

    it('interview has required fillable attributes', function () {
        $interview = new Interview;
        $fillable = $interview->getFillable();

        expect($fillable)->toContain('user_id');
        expect($fillable)->toContain('job_posting_id');
        expect($fillable)->toContain('title');
        expect($fillable)->toContain('status');
    });

    it('job posting has required fillable attributes', function () {
        $jobPosting = new JobPosting;
        $fillable = $jobPosting->getFillable();

        expect($fillable)->toContain('user_id');
        expect($fillable)->toContain('title');
        expect($fillable)->toContain('company');
        expect($fillable)->toContain('description');
    });
});
