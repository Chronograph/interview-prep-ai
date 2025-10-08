<?php

use App\Models\User;

test('guest cannot access protected routes', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');

    $response = $this->get('/interviews');
    $response->assertRedirect('/login');

    $response = $this->get('/resumes');
    $response->assertRedirect('/login');
});

test('user can register', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post('/register', $userData);

    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);
});

test('user can login with valid credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $response = $this->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('registration validation works correctly', function () {
    // Test without required fields
    $response = $this->post('/register', []);
    $response->assertSessionHasErrors(['name', 'email', 'password']);

    // Test with invalid email
    $response = $this->post('/register', [
        'name' => 'Test',
        'email' => 'invalid-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertSessionHasErrors(['email']);

    // Test with mismatched passwords
    $response = $this->post('/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'different',
    ]);
    $response->assertSessionHasErrors(['password']);
});

test('login validation works correctly', function () {
    // Test without credentials
    $response = $this->post('/login', []);
    $response->assertSessionHasErrors(['email', 'password']);

    // Test with invalid email format
    $response = $this->post('/login', [
        'email' => 'invalid-email',
        'password' => 'password',
    ]);
    $response->assertSessionHasErrors(['email']);
});

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/dashboard');

    $response->assertStatus(200);
    $response->assertViewIs('dashboard');
});

test('authenticated user can access interviews', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/interviews');

    $response->assertStatus(200);
});

test('authentication middleware redirects guest users', function () {
    $protectedRoutes = [
        '/dashboard',
        '/interviews',
        '/resumes',
        '/job-postings',
    ];

    foreach ($protectedRoutes as $route) {
        $response = $this->get($route);
        $response->assertRedirect('/login');
    }
});

test('user registration creates user with correct attributes', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'securepassword',
        'password_confirmation' => 'securepassword',
    ];

    $this->post('/register', $userData);

    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $user = User::where('email', 'john@example.com')->first();
    expect($user->name)->toBe('John Doe');
    expect($user->email)->toBe('john@example.com');
});

test('remember me functionality works', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'remember' => 'on',
    ]);

    $response->assertRedirect('/dashboard');

    // Check if remember token is set
    $this->assertNotEmpty($user->getRememberToken());
    $this->assertDatabaseHas('users', [
        'remember_token' => $user->getRememberToken(),
    ]);
});

test('guest can view welcome page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('welcome');
});
