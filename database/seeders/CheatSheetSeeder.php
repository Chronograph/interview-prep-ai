<?php

namespace Database\Seeders;

use App\Models\CheatSheet;
use App\Models\JobPosting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CheatSheetSeeder extends Seeder
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

        $this->command->info('Creating cheat sheets for user: '.$user->email);

        // Create sample job postings first
        $googleJob = JobPosting::create([
            'user_id' => $user->id,
            'title' => 'Senior Product Manager',
            'company' => 'Google',
            'location' => 'Mountain View, CA',
            'description' => 'Lead product strategy and execution for Google\'s core products.',
            'skills' => ['Product Strategy', 'Data Analysis'],
            'is_active' => true,
        ]);

        $amazonJob = JobPosting::create([
            'user_id' => $user->id,
            'title' => 'Product Manager - AWS',
            'company' => 'Amazon',
            'location' => 'Seattle, WA',
            'description' => 'Drive product vision and strategy for AWS services.',
            'skills' => ['Cloud Computing', 'Product Management'],
            'is_active' => true,
        ]);

        $stripeJob = JobPosting::create([
            'user_id' => $user->id,
            'title' => 'Product Manager - Payments',
            'company' => 'Stripe',
            'location' => 'San Francisco, CA',
            'description' => 'Own the product roadmap for Stripe\'s payment infrastructure.',
            'skills' => ['Payments', 'FinTech'],
            'is_active' => true,
        ]);

        // Create cheat sheets for Google
        CheatSheet::create([
            'user_id' => $user->id,
            'job_posting_id' => $googleJob->id,
            'title' => 'Google Product Manager Interview',
            'category' => 'company_specific',
            'topic_description' => 'Comprehensive preparation guide for Google PM interviews',
            'interview_date' => Carbon::parse('2024-10-14'),
            'key_points' => [
                'Google\'s mission: Organize the world\'s information',
                'Focus on user impact and scale',
                'Data-driven decision making',
                'Cross-functional collaboration',
                'Innovation and moonshot thinking',
            ],
            'suggested_response' => 'When discussing product strategy at Google, emphasize user-centric design, data-driven decisions, and the ability to work at scale. Highlight experience with large user bases and complex technical challenges.',
            'examples' => [
                'Experience leading products with 1M+ users',
                'Successfully launched features that improved user engagement by 25%',
                'Led cross-functional teams of 10+ engineers and designers',
            ],
            'follow_up_questions' => [
                'How would you prioritize features for a product with 1 billion users?',
                'Describe a time you used data to make a difficult product decision',
                'How do you handle conflicting requirements from different stakeholders?',
                'What metrics would you track for this product?',
            ],
            'usage_count' => 5,
            'average_score' => 8.5,
            'last_practiced_at' => Carbon::now()->subDays(2),
            'is_custom' => false,
        ]);

        // Create cheat sheets for Amazon
        CheatSheet::create([
            'user_id' => $user->id,
            'job_posting_id' => $amazonJob->id,
            'title' => 'Amazon AWS Product Manager Interview',
            'category' => 'company_specific',
            'topic_description' => 'Preparation guide for Amazon AWS PM role',
            'interview_date' => Carbon::parse('2024-10-07'),
            'key_points' => [
                'Amazon\'s leadership principles',
                'Customer obsession',
                'Cloud computing fundamentals',
                'Scalability and reliability',
            ],
            'suggested_response' => 'Amazon values customer obsession above all. When discussing product decisions, always start with the customer problem and work backwards. Emphasize experience with cloud technologies and understanding of enterprise needs.',
            'examples' => [
                'Led migration of legacy system to cloud infrastructure',
                'Improved system reliability from 99.5% to 99.9% uptime',
            ],
            'follow_up_questions' => [
                'How would you design a new AWS service from scratch?',
                'Describe a time you had to make a decision with incomplete data',
                'How do you ensure customer feedback drives product decisions?',
            ],
            'usage_count' => 3,
            'average_score' => 7.8,
            'last_practiced_at' => Carbon::now()->subDays(5),
            'is_custom' => false,
        ]);

        // Create cheat sheets for Stripe
        CheatSheet::create([
            'user_id' => $user->id,
            'job_posting_id' => $stripeJob->id,
            'title' => 'Stripe Payments Product Manager Interview',
            'category' => 'company_specific',
            'topic_description' => 'Interview preparation for Stripe payments PM role',
            'interview_date' => null, // No interview date set
            'key_points' => [
                'Payments industry knowledge',
                'API design and developer experience',
            ],
            'suggested_response' => 'Stripe values developer experience and simplicity. When discussing product decisions, focus on how to make complex financial operations simple for developers. Emphasize understanding of payments ecosystem and regulatory requirements.',
            'examples' => [
                'Designed API that reduced integration time from weeks to hours',
                'Led compliance initiative that expanded service to 10 new countries',
            ],
            'follow_up_questions' => [
                'How would you design a payment API for a new market?',
                'Describe how you would handle a security incident in payments',
            ],
            'usage_count' => 2,
            'average_score' => 8.2,
            'last_practiced_at' => Carbon::now()->subDays(7),
            'is_custom' => false,
        ]);

        // Create some role-based cheat sheets (without job postings)
        CheatSheet::create([
            'user_id' => $user->id,
            'job_posting_id' => null,
            'title' => 'Behavioral Questions - Leadership',
            'category' => 'behavioral',
            'topic_description' => 'Common behavioral questions about leadership and team management',
            'interview_date' => null,
            'key_points' => [
                'Situational leadership',
                'Conflict resolution',
                'Team building and motivation',
                'Decision making under pressure',
                'Mentoring and development',
            ],
            'suggested_response' => 'Use the STAR method (Situation, Task, Action, Result) to structure your answers. Focus on specific examples where you demonstrated leadership and achieved measurable results.',
            'examples' => [
                'Led team of 8 engineers through major product launch',
                'Resolved conflict between engineering and design teams',
                'Mentored junior PM who was promoted within 6 months',
            ],
            'follow_up_questions' => [
                'Tell me about a time you had to make an unpopular decision',
                'Describe a situation where you had to lead without authority',
                'How do you handle underperforming team members?',
            ],
            'usage_count' => 12,
            'average_score' => 9.1,
            'last_practiced_at' => Carbon::now()->subDays(1),
            'is_custom' => false,
        ]);

        CheatSheet::create([
            'user_id' => $user->id,
            'job_posting_id' => null,
            'title' => 'Product Strategy Questions',
            'category' => 'technical',
            'topic_description' => 'Technical product strategy and roadmap questions',
            'interview_date' => null,
            'key_points' => [
                'Product roadmap planning',
                'Feature prioritization frameworks',
                'Market analysis and competitive positioning',
                'Technical architecture decisions',
                'Metrics and success criteria',
            ],
            'suggested_response' => 'Demonstrate structured thinking by using frameworks like RICE (Reach, Impact, Confidence, Effort) for prioritization. Show understanding of both business and technical considerations.',
            'examples' => [
                'Redesigned product roadmap that increased user retention by 30%',
                'Led technical architecture decision that reduced costs by $2M annually',
                'Implemented A/B testing framework that improved conversion rates',
            ],
            'follow_up_questions' => [
                'How would you prioritize features for a new product launch?',
                'Describe your approach to competitive analysis',
                'How do you measure product success?',
            ],
            'usage_count' => 8,
            'average_score' => 8.7,
            'last_practiced_at' => Carbon::now()->subDays(3),
            'is_custom' => false,
        ]);

        CheatSheet::create([
            'user_id' => $user->id,
            'job_posting_id' => null,
            'title' => 'Data Analysis and Metrics',
            'category' => 'technical',
            'topic_description' => 'Questions about data-driven decision making and analytics',
            'interview_date' => null,
            'key_points' => [
                'SQL and data analysis skills',
                'A/B testing and experimentation',
                'Key product metrics (DAU, MAU, retention)',
                'Statistical significance and sample sizes',
                'Data visualization and storytelling',
            ],
            'suggested_response' => 'Show proficiency in both technical data analysis and business interpretation. Emphasize how you use data to drive product decisions and measure impact.',
            'examples' => [
                'Analyzed user behavior data to identify 20% improvement opportunity',
                'Designed A/B test that increased conversion by 15%',
                'Built dashboard that reduced decision-making time by 50%',
            ],
            'follow_up_questions' => [
                'How do you determine if an A/B test result is statistically significant?',
                'Describe a time data analysis changed your product direction',
                'How do you communicate complex data insights to stakeholders?',
            ],
            'usage_count' => 6,
            'average_score' => 8.3,
            'last_practiced_at' => Carbon::now()->subDays(4),
            'is_custom' => false,
        ]);

        $this->command->info('Cheat sheets seeded successfully!');
        $this->command->info('Created 6 cheat sheets: 3 company-specific and 3 role-based guides.');
    }
}
