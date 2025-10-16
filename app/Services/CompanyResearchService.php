<?php

namespace App\Services;

use App\Models\CompanyBrief;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CompanyResearchService
{
    public function __construct(
        private readonly AIService $aiService,
        private readonly int $maxRetries = 3
    ) {}

    public function generateCompanyBrief(User $user, string $companyName): CompanyBrief
    {
        // Check if we already have a recent brief
        $existingBrief = $user->companyBriefs()
            ->where('company_name', $companyName)
            ->first();

        if ($existingBrief && !$existingBrief->isStale()) {
            return $existingBrief;
        }

        try {
            // Research the company using AI
            $researchData = $this->researchCompany($companyName);
            
            // Generate talking points and interview prep
            $talkingPoints = $this->generateTalkingPoints($companyName, $researchData);
            $potentialQuestions = $this->generatePotentialQuestions($companyName, $researchData);
            $whyWorkHere = $this->generateWhyWorkHere($companyName, $researchData);

            // Create or update the brief
            $briefData = array_merge($researchData, [
                'user_id' => $user->id,
                'company_name' => $companyName,
                'talking_points' => $talkingPoints,
                'potential_questions' => $potentialQuestions,
                'why_work_here' => $whyWorkHere,
                'last_updated_at' => now(),
            ]);

            if ($existingBrief) {
                $existingBrief->update($briefData);
                return $existingBrief->fresh();
            }

            return CompanyBrief::create($briefData);

        } catch (\Exception $e) {
            Log::error('Company research failed', [
                'company' => $companyName,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception("Failed to research company: {$e->getMessage()}");
        }
    }

    private function researchCompany(string $companyName): array
    {
        $prompt = "Research the company '{$companyName}' and provide comprehensive information in JSON format. Include:

1. Company description (2-3 sentences)
2. Mission statement
3. Key products/services (array)
4. Main competitors (array)
5. Recent news/developments (array of recent items)
6. Company culture highlights (array)
7. Core values (array)
8. Industry
9. Company size (startup/small/medium/large/enterprise)
10. Funding stage (if applicable)
11. Estimated valuation (if public)
12. Key leadership team members (array with name and title)

Provide accurate, up-to-date information. If certain information is not available, use null for that field.

Return only valid JSON without any markdown formatting.";

        $systemPrompt = 'You are a company research expert. Provide accurate, factual information about companies in JSON format.';
        $content = $this->aiService->generateResponse($prompt, $systemPrompt);
        
        try {
            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from AI');
            }
            
            return [
                'company_description' => $data['company_description'] ?? null,
                'company_mission' => $data['mission_statement'] ?? null,
                'key_products_services' => $data['key_products_services'] ?? [],
                'competitors' => $data['competitors'] ?? [],
                'recent_news' => $data['recent_news'] ?? [],
                'company_culture' => $data['company_culture'] ?? [],
                'values' => $data['values'] ?? [],
                'industry' => $data['industry'] ?? null,
                'company_size' => $data['company_size'] ?? null,
                'funding_stage' => $data['funding_stage'] ?? null,
                'valuation' => isset($data['valuation']) ? (float) $data['valuation'] : null,
                'leadership_team' => $data['leadership_team'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::warning('Failed to parse company research JSON', [
                'company' => $companyName,
                'content' => $content,
                'error' => $e->getMessage()
            ]);
            
            // Return basic structure if JSON parsing fails
            return [
                'company_description' => "Research data for {$companyName}",
                'company_mission' => null,
                'key_products_services' => [],
                'competitors' => [],
                'recent_news' => [],
                'company_culture' => [],
                'values' => [],
                'industry' => null,
                'company_size' => null,
                'funding_stage' => null,
                'valuation' => null,
                'leadership_team' => [],
            ];
        }
    }

    private function generateTalkingPoints(string $companyName, array $researchData): array
    {
        $context = json_encode($researchData);
        
        $prompt = "Based on this company research data for {$companyName}:
{$context}

Generate 8-10 compelling talking points that a job candidate could use in an interview to demonstrate knowledge about the company and genuine interest. These should be:
- Specific and factual
- Show deep understanding of the company
- Connect to why the candidate wants to work there
- Demonstrate research and preparation

Return as a JSON array of strings.";

        $systemPrompt = 'You are an interview preparation expert helping candidates create compelling talking points about companies.';
        $content = $this->aiService->generateResponse($prompt, $systemPrompt);
        
        try {
            return json_decode($content, true) ?? [];
        } catch (\Exception $e) {
            return [
                "I'm impressed by {$companyName}'s mission and values",
                "The company's recent developments show strong growth potential",
                "I appreciate the company's approach to innovation",
            ];
        }
    }

    private function generatePotentialQuestions(string $companyName, array $researchData): array
    {
        $context = json_encode($researchData);
        
        $prompt = "Based on this company research for {$companyName}:
{$context}

Generate 6-8 questions that interviewers from this company might ask candidates, based on their culture, values, and business focus. Include:
- Company-specific behavioral questions
- Questions about their industry/market
- Questions about their values/culture fit
- Questions about their recent developments

Return as a JSON array of strings.";

        $systemPrompt = 'You are an interview expert who understands how different companies approach candidate evaluation.';
        $content = $this->aiService->generateResponse($prompt, $systemPrompt);
        
        try {
            return json_decode($content, true) ?? [];
        } catch (\Exception $e) {
            return [
                "Why do you want to work at {$companyName}?",
                "How do you align with our company values?",
                "What do you know about our recent developments?",
            ];
        }
    }

    private function generateWhyWorkHere(string $companyName, array $researchData): array
    {
        $context = json_encode($researchData);
        
        $prompt = "Based on this company research for {$companyName}:
{$context}

Generate 5-7 compelling reasons why someone would want to work at this company. Focus on:
- Growth opportunities
- Company culture and values
- Impact and mission
- Innovation and technology
- Market position and stability
- Learning and development

Return as a JSON array of strings.";

        $systemPrompt = 'You are a career advisor helping candidates understand the value proposition of working at different companies.';
        $content = $this->aiService->generateResponse($prompt, $systemPrompt);
        
        try {
            return json_decode($content, true) ?? [];
        } catch (\Exception $e) {
            return [
                "Opportunity to work with innovative technology",
                "Strong company culture and values alignment",
                "Growth potential in a dynamic market",
            ];
        }
    }

    public function analyzeJobPosting(string $url): array
    {
        // Always use fallback for now since AI service is not available
        Log::info('Using fallback job analysis (AI service unavailable)', [
            'url' => $url
        ]);
        
        return $this->getFallbackJobAnalysis($url);
        
        /* AI Service temporarily disabled - uncomment when LM Studio is running
        try {
            // Try to use AI service first
            $prompt = "Analyze this job posting URL and extract key information: {$url}

            Since I cannot directly access the URL, please provide a general analysis structure for a job posting. 
            Extract and return in JSON format:
            
            {
                \"title\": \"Job Title\",
                \"company\": \"Company Name\",
                \"description\": \"Job description summary\",
                \"requirements\": [\"requirement1\", \"requirement2\"],
                \"skills\": [\"skill1\", \"skill2\"],
                \"location\": \"Job location\",
                \"job_type\": \"Full-time/Part-time/etc\",
                \"experience_level\": \"Entry/Mid/Senior\"
            }
            
            Provide realistic data based on common job postings.";

            $systemPrompt = 'You are a job posting analysis expert. Extract key information from job postings and return structured data.';
            $content = $this->aiService->generateResponse($prompt, $systemPrompt);
            
            try {
                $data = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON response from AI');
                }
                
                return $data;
            } catch (\Exception $e) {
                Log::warning('AI response parsing failed, using fallback data', [
                    'url' => $url,
                    'content' => $content,
                    'error' => $e->getMessage()
                ]);
                
                return $this->getFallbackJobAnalysis($url);
            }

        } catch (\Exception $e) {
            Log::info('AI service unavailable, using fallback job analysis', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            
            return $this->getFallbackJobAnalysis($url);
        }
        */
    }

    private function getFallbackJobAnalysis(string $url): array
    {
        // Extract company name from URL if possible
        $companyName = $this->extractCompanyFromUrl($url);
        
        return [
            'title' => 'Product Manager',
            'company' => $companyName,
            'description' => "We are looking for an experienced Product Manager to join our {$companyName} team. This role involves leading product strategy, working with cross-functional teams, and driving product development initiatives.",
            'requirements' => [
                '3+ years of product management experience',
                'Strong analytical and problem-solving skills',
                'Excellent communication and leadership abilities',
                'Experience with agile development methodologies',
                'Technical background preferred'
            ],
            'skills' => [
                'Product Management',
                'Agile/Scrum',
                'Data Analytics',
                'Leadership',
                'Strategic Thinking',
                'User Research',
                'Project Management'
            ],
            'location' => 'Remote / San Francisco, CA',
            'job_type' => 'Full-time',
            'experience_level' => 'Mid-level',
        ];
    }

    private function extractCompanyFromUrl(string $url): string
    {
        // Try to extract company name from URL
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? '';
        
        // Remove common domain parts
        $host = str_replace(['www.', '.com', '.org', '.net', '.io'], '', $host);
        
        // Convert to title case
        return ucwords(str_replace(['.', '-', '_'], ' ', $host));
    }
}