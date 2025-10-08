<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResumeSeeder extends Seeder
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

        $this->command->info('Creating resumes for: '.$user->email);

        // Resume 1: Product Manager Resume - Tech Focus
        $resume1 = Resume::create([
            'user_id' => $user->id,
            'title' => 'Product Manager Resume - Tech Focus',
            'version' => 3,
            // Personal Information
            'full_name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@email.com',
            'phone' => '+1 (415) 555-0123',
            'location' => 'San Francisco, CA',
            'linkedin_url' => 'https://linkedin.com/in/sarahjohnson',
            'portfolio_url' => 'https://sarahjohnson.com',
            'github_url' => 'https://github.com/sarahjohnson',
            // Professional Details
            'headline' => 'Senior Product Manager | SaaS & Mobile Expert | Data-Driven Leader',
            'summary' => 'Senior Product Manager with 8+ years of experience leading cross-functional teams to deliver innovative software products. Specialized in SaaS, mobile applications, and data-driven decision making.',
            'objective' => 'Seeking a senior product leadership role at a high-growth tech company where I can leverage my experience in scaling products and teams.',
            'skills' => [
                'Product Strategy',
                'Agile/Scrum',
                'User Research',
                'Data Analytics',
                'SQL',
                'A/B Testing',
                'Roadmap Planning',
                'Stakeholder Management',
                'Jira',
                'Figma',
            ],
            'experience' => [
                'Senior Product Manager at TechCorp (2020-Present) - Led team of 5 PMs, scaled user base from 100K to 1M',
                'Product Manager at StartupXYZ (2017-2020) - Launched 3 major features, increased retention by 35%',
                'Associate Product Manager at BigTech Inc (2015-2017) - Managed mobile app with 500K+ downloads',
            ],
            'education' => [
                'MBA, Stanford Graduate School of Business (2015)',
                'BS Computer Science, UC Berkeley (2013)',
            ],
            'certifications' => [
                'Certified Scrum Product Owner (CSPO)',
                'Google Analytics Certification',
                'Product Management Certificate - General Assembly',
            ],
            'projects' => [
                'Led mobile app redesign increasing user engagement by 45%',
                'Launched SaaS platform serving 10K+ customers with $5M ARR',
                'Built and scaled analytics dashboard used by 200+ enterprise clients',
            ],
            'languages' => ['English (Native)', 'Spanish (Professional)'],
            'awards' => [
                'Product Leader of the Year 2023 - TechCorp',
                '40 Under 40 in Tech - Tech Magazine',
            ],
            'publications' => [
                'Building Data-Driven Products - Medium (10K+ views)',
                'The Future of SaaS - Product Coalition',
            ],
            'volunteer_work' => [
                'Product Mentor at Product School (2022-Present)',
                'STEM Education Volunteer - Girls Who Code',
            ],
            'interests' => ['Product Design', 'AI/ML', 'Hiking', 'Photography'],
            'is_primary' => true,
            'file_size' => 245,
            'optimized_companies' => ['Google', 'Meta', 'Amazon'],
            'optimized_roles' => ['Senior Product Manager', 'Product Manager II', 'Technical Product Manager'],
        ]);
        $resume1->updateOptimizationScore();

        // Create applications and interviews for resume 1
        $this->createApplicationsForResume($resume1, 5, 4, 2);

        // Resume 2: Software Engineer Resume
        $resume2 = Resume::create([
            'user_id' => $user->id,
            'title' => 'Full Stack Software Engineer Resume',
            'version' => 2,
            // Personal Information
            'full_name' => 'Michael Chen',
            'email' => 'michael.chen@email.com',
            'phone' => '+1 (650) 555-0456',
            'location' => 'Seattle, WA',
            'linkedin_url' => 'https://linkedin.com/in/michaelchen',
            'portfolio_url' => 'https://michaelchen.dev',
            'github_url' => 'https://github.com/mchen',
            // Professional Details
            'headline' => 'Full Stack Software Engineer | React & Node.js Specialist | Cloud Architecture',
            'summary' => 'Passionate full-stack developer with 5+ years of experience building scalable web applications. Expert in React, Node.js, and cloud infrastructure.',
            'objective' => 'Looking to join a forward-thinking company where I can contribute to building innovative products at scale.',
            'skills' => [
                'JavaScript',
                'TypeScript',
                'React',
                'Node.js',
                'Python',
                'PostgreSQL',
                'AWS',
                'Docker',
                'Git',
                'REST APIs',
            ],
            'experience' => [
                'Senior Software Engineer at CloudTech (2021-Present) - Built scalable microservices, led team of 4',
                'Software Engineer at DevShop (2019-2021) - Developed e-commerce platform serving 100K+ users',
                'Junior Developer at WebCo (2018-2019) - Created responsive web applications',
            ],
            'education' => [
                'BS Computer Science, MIT (2018)',
                'Full Stack Web Development Bootcamp - Lambda School (2017)',
            ],
            'certifications' => [
                'AWS Certified Solutions Architect - Associate',
                'Meta React Certification',
                'MongoDB Certified Developer',
            ],
            'projects' => [
                'Built microservices architecture handling 1M+ requests/day',
                'Open source contributor to React ecosystem (500+ stars)',
                'Created developer tool used by 5K+ developers',
            ],
            'languages' => ['English (Native)', 'Mandarin (Conversational)'],
            'awards' => [
                'Best Hackathon Project - TechCrunch Disrupt 2022',
                'Employee Excellence Award - CloudTech',
            ],
            'publications' => [
                'Modern React Patterns - Dev.to (5K+ views)',
                'Building Scalable APIs - Medium',
            ],
            'volunteer_work' => [
                'Code mentor at Codepath.org',
                'Volunteer instructor - FreeCodeCamp',
            ],
            'interests' => ['Open Source', 'Tech Blogging', 'Gaming', 'Basketball'],
            'is_primary' => false,
            'file_size' => 198,
            'optimized_companies' => ['Microsoft', 'Netflix', 'Stripe'],
            'optimized_roles' => ['Senior Software Engineer', 'Full Stack Developer', 'Backend Engineer'],
        ]);
        $resume2->updateOptimizationScore();

        // Create applications for resume 2
        $this->createApplicationsForResume($resume2, 8, 6, 3);

        // Resume 3: Data Scientist Resume
        $resume3 = Resume::create([
            'user_id' => $user->id,
            'title' => 'Data Scientist - ML Focus',
            'version' => 1,
            // Personal Information
            'full_name' => 'Emily Rodriguez',
            'email' => 'emily.rodriguez@email.com',
            'phone' => '+1 (408) 555-0789',
            'location' => 'Palo Alto, CA',
            'linkedin_url' => 'https://linkedin.com/in/emilyrodriguez',
            'portfolio_url' => 'https://emilyrodriguez.ai',
            'github_url' => 'https://github.com/erodriguez',
            // Professional Details
            'headline' => 'Data Scientist | ML Engineer | AI Researcher',
            'summary' => 'Data Scientist with expertise in machine learning, statistical modeling, and big data analytics. Proven track record of delivering business value through data-driven insights.',
            'objective' => 'Passionate about leveraging AI and ML to solve complex business problems and drive innovation in a research-focused environment.',
            'skills' => [
                'Python',
                'R',
                'Machine Learning',
                'Deep Learning',
                'TensorFlow',
                'PyTorch',
                'SQL',
                'Spark',
                'Tableau',
                'Statistics',
            ],
            'experience' => [
                'Data Scientist at AI Corp (2020-Present) - Led ML projects, deployed 5+ production models',
                'Data Analyst at Analytics Inc (2018-2020) - Built dashboards and predictive models',
                'Research Assistant at CMU (2016-2018) - Published 3 papers on machine learning',
            ],
            'education' => [
                'MS Data Science, Carnegie Mellon University (2018)',
                'BS Mathematics, UCLA (2016)',
            ],
            'certifications' => [
                'TensorFlow Developer Certificate - Google',
                'AWS Machine Learning Specialty',
                'Deep Learning Specialization - Coursera',
            ],
            'projects' => [
                'Developed recommendation engine increasing revenue by 30%',
                'Built fraud detection model with 95% accuracy saving $2M annually',
                'Created NLP pipeline processing 1M+ documents',
            ],
            'languages' => ['English (Native)', 'Spanish (Native)', 'French (Intermediate)'],
            'awards' => [
                'Best Research Paper Award - ML Conference 2022',
                'Data Science Competition Winner - Kaggle (Top 1%)',
            ],
            'publications' => [
                'Deep Learning for Fraud Detection - IEEE Journal',
                'Neural Networks in Production - AI Magazine',
                'Machine Learning Blog - 15K+ monthly readers',
            ],
            'volunteer_work' => [
                'AI Ethics Committee Member - Tech for Good',
                'Data Science Mentor - Women in Data Science',
            ],
            'interests' => ['AI Research', 'Classical Music', 'Rock Climbing', 'Chess'],
            'is_primary' => false,
            'file_size' => 215,
            'optimized_companies' => ['OpenAI', 'DeepMind', 'Databricks'],
            'optimized_roles' => ['Data Scientist', 'ML Engineer', 'AI Research Scientist'],
        ]);
        $resume3->updateOptimizationScore();

        // Create applications for resume 3
        $this->createApplicationsForResume($resume3, 3, 2, 1);

        $this->command->info('Created 3 resumes with applications and interviews!');
    }

    /**
     * Create applications and interviews for a resume
     */
    private function createApplicationsForResume(Resume $resume, int $totalApps, int $responses, int $interviews): void
    {
        for ($i = 0; $i < $totalApps; $i++) {
            $application = Application::create([
                'user_id' => $resume->user_id,
                'resume_id' => $resume->id,
                'company_name' => $this->getRandomCompany(),
                'position_title' => $this->getRandomPosition(),
                'status' => $this->getRandomStatus(),
                'got_response' => $i < $responses,
                'priority' => ['high', 'medium', 'low'][array_rand(['high', 'medium', 'low'])],
                'application_date' => now()->subDays(rand(1, 60)),
                'location' => ['San Francisco, CA', 'New York, NY', 'Seattle, WA', 'Austin, TX', 'Remote'][array_rand(['San Francisco, CA', 'New York, NY', 'Seattle, WA', 'Austin, TX', 'Remote'])],
                'work_type' => ['full-time', 'contract', 'remote'][array_rand(['full-time', 'contract', 'remote'])],
            ]);

            // Create interviews for some applications
            if ($i < $interviews) {
                Interview::create([
                    'user_id' => $resume->user_id,
                    'resume_id' => $resume->id,
                    'title' => $application->position_title.' Interview at '.$application->company_name,
                    'description' => 'Interview for '.$application->position_title.' position',
                    'interview_type' => ['behavioral', 'technical', 'mixed'][array_rand(['behavioral', 'technical', 'mixed'])],
                    'status' => ['pending', 'in_progress', 'completed'][array_rand(['pending', 'in_progress', 'completed'])],
                    'duration_minutes' => [30, 45, 60][array_rand([30, 45, 60])],
                    'started_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                ]);
            }
        }
    }

    private function getRandomCompany(): string
    {
        $companies = [
            'Google', 'Meta', 'Amazon', 'Apple', 'Microsoft',
            'Netflix', 'Uber', 'Airbnb', 'Stripe', 'Salesforce',
            'Adobe', 'Slack', 'Zoom', 'Dropbox', 'Twitter',
            'LinkedIn', 'Shopify', 'Square', 'Coinbase', 'Palantir',
        ];

        return $companies[array_rand($companies)];
    }

    private function getRandomPosition(): string
    {
        $positions = [
            'Senior Software Engineer',
            'Product Manager',
            'Data Scientist',
            'DevOps Engineer',
            'Frontend Developer',
            'Backend Engineer',
            'Full Stack Developer',
            'ML Engineer',
            'Engineering Manager',
            'Technical Lead',
        ];

        return $positions[array_rand($positions)];
    }

    private function getRandomStatus(): string
    {
        $statuses = [
            'applied',
            'screening',
            'interviewing',
            'offer',
            'accepted',
            'rejected',
            'withdrawn',
        ];

        return $statuses[array_rand($statuses)];
    }
}
