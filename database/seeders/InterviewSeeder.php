<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Seeder;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user
        $user = User::where('email', 'test@example.com')->first();

        if (! $user) {
            $this->command->error('Test user not found! Please run DatabaseSeeder first.');

            return;
        }

        $this->command->info('Creating interviews for user: '.$user->email);

        // Create sample interviews to match the design
        Interview::create([
            'user_id' => $user->id,
            'title' => 'Senior Product Manager Interview',
            'interview_type' => 'mixed',
            'status' => 'pending',
            'description' => 'Final round interview for Senior Product Manager position at Meta.',
            'duration_minutes' => 60,
        ]);

        Interview::create([
            'user_id' => $user->id,
            'title' => 'Product Manager Interview',
            'interview_type' => 'mixed',
            'status' => 'pending',
            'description' => 'Technical interview for Product Manager position at Google.',
            'duration_minutes' => 45,
        ]);

        Interview::create([
            'user_id' => $user->id,
            'title' => 'Principal Product Manager Interview',
            'interview_type' => 'technical',
            'status' => 'pending',
            'description' => 'On-site interview for Principal Product Manager position at Apple.',
            'duration_minutes' => 120,
        ]);

        $this->command->info('Interviews seeded successfully!');
        $this->command->info('Created 3 upcoming interviews to match the design.');
    }
}
