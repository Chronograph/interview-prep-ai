<?php

use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('user can view resumes index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/resumes');

    $response->assertStatus(200);
});

test('user can create resume', function () {
    $user = User::factory()->create();

    $resumeData = [
        'title' => 'Software Engineer Resume',
        'summary' => 'Experienced software engineer',
        'skills' => ['PHP', 'Laravel', 'JavaScript'],
    ];

    $response = $this->actingAs($user)
        ->post('/resumes', $resumeData);

    $response->assertRedirect();

    expect(Resume::count())->toBe(1);

    $resume = Resume::first();
    expect($resume->user_id)->toBe($user->id);
    expect($resume->title)->toBe('Software Engineer Resume');
    expect($resume->summary)->toBe('Experienced software engineer');
});

test('user can update resume', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Resume',
    ]);

    $updateData = [
        'title' => 'Updated Resume',
        'summary' => 'Updated summary',
        'skills' => ['PHP', 'Laravel', 'Vue.js', 'Tailwind'],
    ];

    $response = $this->actingAs($user)
        ->put("/resumes/{$resume->id}", $updateData);

    $response->assertRedirect();

    $resume->refresh();
    expect($resume->title)->toBe('Updated Resume');
    expect($resume->summary)->toBe('Updated summary');
});

test('user can delete resume', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user->id]);

    expect(Resume::count())->toBe(1);

    $response = $this->actingAs($user)
        ->delete("/resumes/{$resume->id}");

    $response->assertRedirect();
    expect(Resume::count())->toBe(0);
});

test('user cannot access other user resume', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user2->id]);

    $response = $this->actingAs($user1)
        ->get("/resumes/{$resume->id}");

    $response->assertStatus(403);
});

test('user can upload resume file', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('resume.pdf', 200, 'application/pdf');

    $resumeData = [
        'title' => 'My Resume',
        'summary' => 'Software engineer with 5+ years experience',
        'file' => $file,
    ];

    $response = $this->actingAs($user)
        ->post('/resumes', $resumeData);

    $response->assertRedirect();

    $resume = Resume::first();
    expect($resume->file_path)->not->toBeNull();
    Storage::assertExists($resume->file_path);
});

test('resume validates required fields', function () {
    $user = User::factory()->create();

    // Test without required title
    $response = $this->actingAs($user)
        ->post('/resumes', []);

    $response->assertSessionHasErrors(['title']);
});

test('user can set primary resume', function () {
    $user = User::factory()->create();
    $resume1 = Resume::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
    $resume2 = Resume::factory()->create(['user_id' => $user->id, 'is_primary' => false]);

    $response = $this->actingAs($user)
        ->post("/resumes/{$resume2->id}/set-primary");

    $response->assertRedirect();

    $resume1->refresh();
    $resume2->refresh();

    expect($resume1->is_primary)->toBeFalse();
    expect($resume2->is_primary)->toBeTrue();
});

test('resume requires authentication', function () {
    $resume = Resume::factory()->create();

    $response = $this->get("/resumes/{$resume->id}");

    $response->assertRedirect('/login');
});

test('user can view resume details', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create([
        'user_id' => $user->id,
        'experience' => [
            [
                'company' => 'ABC Corp',
                'title' => 'Developer',
                'start_date' => '2020-01-01',
                'end_date' => '2023-01-01',
            ],
        ],
        'education' => [
            [
                'institution' => 'University',
                'degree' => 'Computer Science',
                'graduation_date' => '2019-01-01',
            ],
        ],
    ]);

    $response = $this->actingAs($user)
        ->get("/resumes/{$resume->id}");

    $response->assertStatus(200)
        ->assertViewHas('resume', $resume);
});

test('user can parse resume content', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create([
        'user_id' => $user->id,
        'file_path' => 'test-resume.pdf',
    ]);

    $response = $this->actingAs($user)
        ->post("/resumes/{$resume->id}/parse");

    expect(Resume::count())->toBe(1);
});

test('resume can get skills as array', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create([
        'user_id' => $user->id,
        'skills' => ['PHP', 'Laravel', 'Vue.js'],
    ]);

    expect($resume->skills)->toBeArray();
    expect($resume->skills)->toEqual(['PHP', 'Laravel', 'Vue.js']);
});
