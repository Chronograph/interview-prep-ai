<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\MasteryScore;
use App\Models\TopicProgress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or find test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create additional users for testing
        $users = User::factory(3)->create();
        $allUsers = collect([$user])->merge($users);

        // Seed resumes with applications and interviews
        $this->call(ResumeSeeder::class);

        // Seed cheat sheets
        $this->call(CheatSheetSeeder::class);

        // Seed applications
        $this->call(ApplicationSeeder::class);

        // Seed interviews
        $this->call(InterviewSeeder::class);

        // Create mastery scores for different topics and skills
        $topics = ['JavaScript', 'PHP', 'Laravel', 'Vue.js', 'React', 'Node.js', 'Python', 'SQL'];
        $skills = ['Problem Solving', 'Communication', 'Technical Knowledge', 'Code Quality', 'Debugging'];

        foreach ($allUsers as $testUser) {
            foreach ($topics as $topic) {
                foreach ($skills as $skill) {
                    $score = rand(60, 100);
                    $attempts = rand(1, 20);
                    $improvementRate = rand(-5, 15); // Can be negative for decline

                    MasteryScore::firstOrCreate(
                        [
                            'user_id' => $testUser->id,
                            'topic' => $topic,
                            'skill' => $skill,
                        ],
                        [
                            'score' => $score,
                            'attempts' => $attempts,
                            'improvement_rate' => $improvementRate,
                            'last_practiced_at' => now()->subDays(rand(0, 30)),
                            'performance_history' => json_encode([
                                ['score' => rand(40, 80), 'date' => now()->subDays(30)->toDateString()],
                                ['score' => rand(50, 85), 'date' => now()->subDays(15)->toDateString()],
                                ['score' => $score, 'date' => now()->subDays(rand(0, 7))->toDateString()],
                            ]),
                        ]
                    );
                }
            }

            // Create topic progress for different categories
            $progressTopics = [
                'Data Structures' => 'Technical',
                'Algorithms' => 'Technical',
                'System Design' => 'Technical',
                'Behavioral Questions' => 'Behavioral',
                'Leadership' => 'Behavioral',
                'Problem Solving' => 'Case Study',
            ];

            $difficultyLevels = ['beginner', 'intermediate', 'advanced'];

            foreach ($progressTopics as $topicName => $category) {
                $questionsAttempted = rand(15, 40);
                $questionsCorrect = rand(5, $questionsAttempted);
                $completionPercentage = rand(30, 95);
                $averageScore = ($questionsAttempted > 0) ? ($questionsCorrect / $questionsAttempted) * 100 : 0;

                TopicProgress::firstOrCreate(
                    [
                        'user_id' => $testUser->id,
                        'topic_name' => $topicName,
                    ],
                    [
                        'category' => $category,
                        'difficulty_level' => $difficultyLevels[array_rand($difficultyLevels)],
                        'completion_percentage' => $completionPercentage,
                        'questions_attempted' => $questionsAttempted,
                        'questions_correct' => $questionsCorrect,
                        'average_score' => round($averageScore, 2),
                        'time_spent_minutes' => rand(30, 300),
                        'strengths' => json_encode(['Quick problem identification', 'Clear communication']),
                        'weaknesses' => json_encode(['Time management', 'Edge case handling']),
                        'last_practiced_at' => now()->subDays(rand(0, 20)),
                    ]
                );
            }
        }

        // Create job applications
        $companies = ['Google', 'Microsoft', 'Amazon', 'Apple', 'Meta', 'Netflix', 'Spotify', 'Uber'];
        $positions = ['Software Engineer', 'Senior Developer', 'Full Stack Developer', 'DevOps Engineer', 'Product Manager'];
        $statuses = ['applied', 'screening', 'phone_interview', 'technical_interview', 'onsite_interview', 'final_interview', 'offer', 'rejected', 'withdrawn'];
        $priorities = ['low', 'medium', 'high'];
        $workTypes = ['remote', 'hybrid', 'onsite'];

        foreach ($allUsers as $testUser) {
            for ($i = 0; $i < rand(5, 15); $i++) {
                Application::create([
                    'user_id' => $testUser->id,
                    'company_name' => $companies[array_rand($companies)],
                    'position_title' => $positions[array_rand($positions)],
                    'application_date' => now()->subDays(rand(1, 180)),
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'job_url' => 'https://example.com/job/'.rand(1000, 9999),
                    'salary_min' => rand(70000, 120000),
                    'salary_max' => rand(120000, 200000),
                    'location' => ['San Francisco, CA', 'New York, NY', 'Seattle, WA', 'Austin, TX'][array_rand(['San Francisco, CA', 'New York, NY', 'Seattle, WA', 'Austin, TX'])],
                    'work_type' => $workTypes[array_rand($workTypes)],
                    'notes' => 'Applied through company website. Waiting for response.',
                    'expected_response_date' => rand(0, 1) ? now()->addDays(rand(1, 30)) : null,
                    'contacts' => json_encode([
                        ['name' => 'John Doe', 'email' => 'john.doe@company.com', 'role' => 'Recruiter'],
                        ['name' => 'Jane Smith', 'email' => 'jane.smith@company.com', 'role' => 'Hiring Manager'],
                    ]),
                    'requirements' => json_encode(['5+ years experience', 'React/Vue.js', 'Node.js', 'AWS']),
                    'interview_stages' => json_encode([
                        ['stage' => 'Phone Screen', 'completed' => true, 'date' => now()->subDays(rand(1, 30))->toDateString()],
                        ['stage' => 'Technical Interview', 'completed' => false, 'date' => null],
                    ]),
                    'is_favorite' => rand(0, 1),
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Test user credentials:');
        $this->command->info('Email: test@example.com');
        $this->command->info('Password: password');
    }
}
