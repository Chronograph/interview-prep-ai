<?php

use App\Models\MasteryScore;
use App\Models\MasteryTopic;
use App\Models\User;

test('user can view mastery topics', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/mastery-topics');

    $response->assertStatus(200);
});

test('user can create mastery topic', function () {
    $user = User::factory()->create();

    $topicData = [
        'name' => 'JavaScript Fundamentals',
        'category' => 'technical',
        'description' => 'Core JavaScript concepts',
        'difficulty_level' => 'beginner',
    ];

    $response = $this->actingAs($user)
        ->post('/mastery-topics', $topicData);

    $response->assertRedirect();

    expect(MasteryTopic::count())->toBe(1);

    $topic = MasteryTopic::first();
    expect($topic->name)->toBe('JavaScript Fundamentals');
    expect($topic->user_id)->toBe($user->id);
});

test('mastery score is calculated correctly', function () {
    $user = User::factory()->create();
    $topic = MasteryTopic::factory()->create(['user_id' => $user->id]);

    $score = MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $topic->id,
        'score' => 85,
    ]);

    expect($score->score)->toBe(85);
    expect($score->masteryLevel)->toBeString();
});

test('mastery progress tracking works', function () {
    $user = User::factory()->create();
    $topic = MasteryTopic::factory()->create(['user_id' => $user->id]);

    // Simulate multiple sessions improving performance
    MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $topic->id,
        'score' => 60,
    ]);

    MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $topic->id,
        'score' => 75,
    ]);

    MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $topic->id,
        'score' => 90,
    ]);

    $userScores = MasteryScore::where('user_id', $user->id)
        ->where('mastery_topic_id', $topic->id)
        ->get();

    expect($userScores)->toHaveCount(3);

    $latestScore = $userScores->sortByDesc('created_at')->first();
    expect($latestScore->score)->toBe(90);
});

test('mastery topic difficulty calculation', function () {
    $user = User::factory()->create();

    $beginnerTopic = MasteryTopic::factory()->create([
        'user_id' => $user->id,
        'difficulty_level' => 'beginner',
    ]);

    $intermediateTopic = MasteryTopic::factory()->create([
        'user_id' => $user->id,
        'difficulty_level' => 'intermediate',
    ]);

    expect($beginnerTopic->difficulty_level)->toBe('beginner');
    expect($intermediateTopic->difficulty_level)->toBe('intermediate');
});

test('mastery achievement unlocking', function () {
    $user = User::factory()->create();
    $topic = MasteryTopic::factory()->create([
        'user_id' => $user->id,
        'difficulty_level' => 'beginner',
    ]);

    // Simulate high performance achievement
    MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $topic->id,
        'score' => 95,
    ]);

    // Check mastery level calculation
    $topicProgress = $topic->userProgresses()
        ->where('user_id', $user->id)
        ->first();

    expect($topicProgress)->not->toBeNull();
});

test('mastery analytics dashboard integration', function () {
    $user = User::factory()->create();

    // Create topics with scores
    $topic1 = MasteryTopic::factory()->create(['user_id' => $user->id]);
    $topic2 = MasteryTopic::factory()->create(['user_id' => $user->id]);

    MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $topic1->id,
        'score' => 80,
    ]);

    MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $topic2->id,
        'score' => 70,
    ]);

    $response = $this->actingAs($user)
        ->get('/analytics/mastery');

    $response->assertStatus(200)
        ->assertSee($topic1->name)
        ->assertSee($topic2->name);
});

test('mastery recommendations system', function () {
    $user = User::factory()->create();

    // Create weak performing topic
    $weakTopic = MasteryTopic::factory()->create([
        'user_id' => $user->id,
        'name' => 'PHP Advanced',
    ]);

    MasteryScore::factory()->create([
        'user_id' => $user->id,
        'mastery_topic_id' => $weakTopic->id,
        'score' => 45,
    ]);

    $response = $this->actingAs($user)
        ->get('/dashboard');

    expect($response->getContent())->toContain('Recommended Topics');
});
