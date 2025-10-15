<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user
        $user = User::where('email', 'test@example.com')->first();

        if (! $user) {
            $this->command->warn('No users found. Please create a user first.');

            return;
        }

        $this->command->info('Creating applications for user: '.$user->email);

        // Create sample applications to match the design
        Application::create([
            'user_id' => $user->id,
            'company_name' => 'Google',
            'position_title' => 'Senior Product Manager',
            'job_url' => 'https://careers.google.com/jobs/results/123456789',
            'status' => 'phone_interview',
            'priority' => 'high',
            'application_date' => Carbon::parse('2024-01-14'),
            'expected_response_date' => Carbon::parse('2024-01-24'),
            'salary_min' => 170000,
            'salary_max' => 220000,
            'location' => 'Mountain View, CA',
            'work_type' => 'hybrid',
            'notes' => 'Applied through company website. Had initial phone screen with recruiter.',
            'is_favorite' => true,
        ]);

        Application::create([
            'user_id' => $user->id,
            'company_name' => 'Microsoft',
            'position_title' => 'Principal Product Manager',
            'job_url' => 'https://careers.microsoft.com/us/en/job/123456',
            'status' => 'applied',
            'priority' => 'medium',
            'application_date' => Carbon::parse('2024-01-11'),
            'expected_response_date' => null,
            'salary_min' => 160000,
            'salary_max' => 200000,
            'location' => 'Seattle, WA',
            'work_type' => 'hybrid',
            'notes' => 'Applied through LinkedIn. Waiting for initial response.',
            'is_favorite' => false,
        ]);

        Application::create([
            'user_id' => $user->id,
            'company_name' => 'Amazon',
            'position_title' => 'Senior UX Design Manager',
            'job_url' => 'https://www.amazon.jobs/en/jobs/1234567',
            'status' => 'technical_interview',
            'priority' => 'high',
            'application_date' => Carbon::parse('2024-01-09'),
            'expected_response_date' => Carbon::parse('2024-01-21'),
            'salary_min' => 140000,
            'salary_max' => 180000,
            'location' => 'Austin, TX',
            'work_type' => 'onsite',
            'notes' => 'Completed initial screening call. Moving to technical interview round.',
            'is_favorite' => true,
        ]);

        Application::create([
            'user_id' => $user->id,
            'company_name' => 'Stripe',
            'position_title' => 'Product Manager - Payments',
            'job_url' => 'https://stripe.com/jobs/listing/123456',
            'status' => 'final_interview',
            'priority' => 'high',
            'application_date' => Carbon::parse('2024-01-05'),
            'expected_response_date' => Carbon::parse('2024-01-18'),
            'salary_min' => 160000,
            'salary_max' => 220000,
            'location' => 'San Francisco, CA',
            'work_type' => 'remote',
            'notes' => 'Completed technical interview. Waiting for final round scheduling.',
            'is_favorite' => true,
        ]);

        Application::create([
            'user_id' => $user->id,
            'company_name' => 'Meta',
            'position_title' => 'Product Manager - Instagram',
            'job_url' => 'https://www.metacareers.com/jobs/123456',
            'status' => 'offer',
            'priority' => 'high',
            'application_date' => Carbon::parse('2023-12-20'),
            'expected_response_date' => null,
            'salary_min' => 175000,
            'salary_max' => 225000,
            'location' => 'Menlo Park, CA',
            'work_type' => 'hybrid',
            'notes' => 'Received offer! Negotiating salary and benefits. Decision needed by end of week.',
            'is_favorite' => true,
        ]);

        $this->command->info('Applications seeded successfully!');
        $this->command->info('Created 5 applications to match the design.');
    }
}
