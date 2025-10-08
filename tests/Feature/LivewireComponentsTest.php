<?php

use App\Livewire\Dashboard;
use App\Livewire\InterviewInterface;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\User;
use Livewire\Livewire;

test('dashboard component displays correctly', function () {
    $user = User::factory()->create();

    // Create some data for dashboard
    JobPosting::factory()->count(3)->create(['user_id' => $user->id]);
    Interview::factory()->count(2)->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertSee('Dashboard Stats')
        ->assertSee('Recent Activity');
});

test('interview interface component functionality', function () {
    $user = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(InterviewInterface::class, ['interview' => $interview])
        ->assertSee($interview->title)
        ->assertMethodWired('startInterview')
        ->assertMethodWired('submitAnswer');
});

test('users can only access their own data in livewire components', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $user2->id]);

    // User1 should not be able to access User2's interview
    Livewire::actingAs($user1)
        ->test(InterviewInterface::class, ['interview' => $interview])
        ->assertForbidden();
});

test('livewire components handle real-time updates', function () {
    $user = User::factory()->create();
    $interview = Interview::factory()->create([
        'user_id' => $user->id,
        'status' => 'in_progress',
    ]);

    $component = Livewire::actingAs($user)
        ->test(InterviewInterface::class, ['interview' => $interview]);

    // Simulate real-time update
    $interview->update(['status' => 'completed']);
    $interview->save();

    $component->emit('interviewUpdated');
    $component->assertSee('Completed');
});
