<?php

use App\Models\CheatSheet;
use App\Models\User;
use App\Services\CheatSheetService;
use Illuminate\Validation\ValidationException;

test('cheat sheet service can create cheat sheets', function () {
    $user = User::factory()->create();
    $service = new CheatSheetService;

    $cheatSheetData = [
        'topic' => 'Behavioral Questions',
        'talking_points' => [
            'Use STAR method',
            'Prepare specific examples',
        ],
        'key_phrases' => [
            'situation, task, action, result',
            'specific, measurable examples',
        ],
        'category' => 'interview_preparation',
    ];

    $cheatSheet = $service->createCheatSheet($user, $cheatSheetData);

    expect($cheatSheet->topic)->toBe('Behavioral Questions');
    expect($cheatSheet->user_id)->toBe($user->id);
    expect($cheatSheet->talking_points)->toBeArray();
});

test('cheat sheet service can search cheat sheets', function () {
    $user = User::factory()->create();
    $service = new CheatSheetService;

    CheatSheet::factory()->create([
        'user_id' => $user->id,
        'topic' => 'JavaScript Questions',
        'talking_points' => ['Closures', 'Promises'],
    ]);

    CheatSheet::factory()->create([
        'user_id' => $user->id,
        'topic' => 'React Interview',
        'talking_points' => ['Hooks', 'Virtual DOM'],
    ]);

    $results = $service->searchCheatSheets($user, 'JavaScript');

    expect($results)->toHaveCount(1);
    expect($results->first()->topic)->toBe('JavaScript Questions');
});

test('cheat sheet service validates required fields', function () {
    $user = User::factory()->create();
    $service = new CheatSheetService;

    expect(function () use ($service, $user) {
        $service->createCheatSheet($user, []);
    })->toThrow(ValidationException::class);
});

test('cheat sheet service can categorize cheat sheets', function () {
    $user = User::factory()->create();
    $service = new CheatSheetService;

    $cheatSheetData = [
        'topic' => 'Algorithms',
        'talking_points' => ['Sorting', 'Search algorithms'],
        'category' => 'technical',
    ];

    $cheatSheet = $service->createCheatSheet($user, $cheatSheetData);

    expect($cheatSheet->category)->toBe('technical');
});

test('cheat sheet service can calculate mastery level', function () {
    $user = User::factory()->create();
    $service = new CheatSheetService;

    $cheatSheet = CheatSheet::factory()->create([
        'user_id' => $user->id,
        'practice_count' => 5,
        'mastery_score' => 85,
    ]);

    $masteryLevel = $service->calculateMasteryLevel($cheatSheet);

    expect($masteryLevel)->toBeString();
    expect(in_array($masteryLevel, ['beginner', 'intermediate', 'advanced', 'expert']))->toBeTrue();
});

test('cheat sheet service can recommend similar cheat sheets', function () {
    $user = User::factory()->create();
    $service = new CheatSheetService;

    $originalCheatSheet = CheatSheet::factory()->create([
        'user_id' => $user->id,
        'topic' => 'JavaScript Fundamentals',
        'category' => 'technical',
    ]);

    // Create similar cheat sheets
    CheatSheet::factory()->create([
        'user_id' => $user->id,
        'topic' => 'JavaScript Advanced',
        'category' => 'technical',
    ]);

    CheatSheet::factory()->create([
        'user_id' => $user->id,
        'topic' => 'Web Development',
        'category' => 'technical',
    ]);

    $recommendations = $service->getSimilarCheatSheets($originalCheatSheet, 2);

    expect($recommendations)->toHaveCount(2);
    expect($recommendations->first()->category)->toBe('technical');
});
