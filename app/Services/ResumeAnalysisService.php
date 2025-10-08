<?php

namespace App\Services;

use App\Models\JobPosting;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Log;

class ResumeAnalysisService
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Analyze a resume and provide comprehensive feedback
     */
    public function analyzeResume(UserDocument $resume, ?JobPosting $jobPosting = null): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'resume_data' => $resume->extracted_data,
                'analysis_type' => 'comprehensive',
            ];

            // Add job posting context if provided
            if ($jobPosting) {
                $context['job_posting'] = [
                    'title' => $jobPosting->title,
                    'company' => $jobPosting->company,
                    'description' => $jobPosting->description,
                    'requirements' => $jobPosting->requirements,
                ];
            }

            $analysis = $this->aiService->analyzeResume($context);

            // Store analysis results
            $resume->update([
                'analysis_results' => $analysis,
                'last_analyzed_at' => now(),
            ]);

            return $analysis;
        } catch (\Exception $e) {
            Log::error('Failed to analyze resume', [
                'resume_id' => $resume->id,
                'job_posting_id' => $jobPosting?->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get ATS optimization suggestions
     */
    public function getATSOptimization(UserDocument $resume, JobPosting $jobPosting): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'job_posting' => [
                    'title' => $jobPosting->title,
                    'description' => $jobPosting->description,
                    'requirements' => $jobPosting->requirements,
                ],
                'analysis_type' => 'ats_optimization',
            ];

            $optimization = $this->aiService->analyzeResume($context);

            return $optimization;
        } catch (\Exception $e) {
            Log::error('Failed to get ATS optimization', [
                'resume_id' => $resume->id,
                'job_posting_id' => $jobPosting->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'error' => 'Failed to generate ATS optimization suggestions',
                'suggestions' => [],
            ];
        }
    }

    /**
     * Generate improvement suggestions for specific sections
     */
    public function getSectionImprovements(UserDocument $resume, string $section): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'resume_data' => $resume->extracted_data,
                'target_section' => $section,
                'analysis_type' => 'section_improvement',
            ];

            $improvements = $this->aiService->analyzeResume($context);

            return $improvements;
        } catch (\Exception $e) {
            Log::error('Failed to get section improvements', [
                'resume_id' => $resume->id,
                'section' => $section,
                'error' => $e->getMessage(),
            ]);

            return [
                'error' => 'Failed to generate section improvements',
                'suggestions' => [],
            ];
        }
    }

    /**
     * Compare resume against job requirements
     */
    public function compareToJob(UserDocument $resume, JobPosting $jobPosting): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'resume_data' => $resume->extracted_data,
                'job_posting' => [
                    'title' => $jobPosting->title,
                    'company' => $jobPosting->company,
                    'description' => $jobPosting->description,
                    'requirements' => $jobPosting->requirements,
                ],
                'analysis_type' => 'job_match',
            ];

            $comparison = $this->aiService->analyzeResume($context);

            return $comparison;
        } catch (\Exception $e) {
            Log::error('Failed to compare resume to job', [
                'resume_id' => $resume->id,
                'job_posting_id' => $jobPosting->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'match_score' => 0,
                'strengths' => [],
                'gaps' => [],
                'recommendations' => [],
            ];
        }
    }

    /**
     * Generate keyword suggestions for better ATS performance
     */
    public function getKeywordSuggestions(UserDocument $resume, JobPosting $jobPosting): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'job_requirements' => $jobPosting->requirements,
                'job_description' => $jobPosting->description,
                'analysis_type' => 'keyword_optimization',
            ];

            $keywords = $this->aiService->analyzeResume($context);

            return $keywords;
        } catch (\Exception $e) {
            Log::error('Failed to get keyword suggestions', [
                'resume_id' => $resume->id,
                'job_posting_id' => $jobPosting->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'missing_keywords' => [],
                'suggested_additions' => [],
                'keyword_density' => [],
            ];
        }
    }

    /**
     * Generate a tailored resume version for a specific job
     */
    public function generateTailoredVersion(UserDocument $resume, JobPosting $jobPosting): array
    {
        try {
            $context = [
                'original_resume' => $resume->content,
                'resume_data' => $resume->extracted_data,
                'job_posting' => [
                    'title' => $jobPosting->title,
                    'company' => $jobPosting->company,
                    'description' => $jobPosting->description,
                    'requirements' => $jobPosting->requirements,
                ],
                'analysis_type' => 'tailored_version',
            ];

            $tailoredResume = $this->aiService->analyzeResume($context);

            return $tailoredResume;
        } catch (\Exception $e) {
            Log::error('Failed to generate tailored resume', [
                'resume_id' => $resume->id,
                'job_posting_id' => $jobPosting->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'error' => 'Failed to generate tailored resume version',
                'suggestions' => [],
            ];
        }
    }

    /**
     * Analyze resume formatting and structure
     */
    public function analyzeFormatting(UserDocument $resume): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'file_type' => $resume->file_type,
                'analysis_type' => 'formatting',
            ];

            $formatting = $this->aiService->analyzeResume($context);

            return $formatting;
        } catch (\Exception $e) {
            Log::error('Failed to analyze resume formatting', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'formatting_score' => 0,
                'issues' => [],
                'recommendations' => [],
            ];
        }
    }

    /**
     * Get industry-specific resume recommendations
     */
    public function getIndustryRecommendations(UserDocument $resume, string $industry): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'resume_data' => $resume->extracted_data,
                'target_industry' => $industry,
                'analysis_type' => 'industry_specific',
            ];

            $recommendations = $this->aiService->analyzeResume($context);

            return $recommendations;
        } catch (\Exception $e) {
            Log::error('Failed to get industry recommendations', [
                'resume_id' => $resume->id,
                'industry' => $industry,
                'error' => $e->getMessage(),
            ]);

            return [
                'recommendations' => [],
                'industry_standards' => [],
                'best_practices' => [],
            ];
        }
    }

    /**
     * Generate resume summary/objective suggestions
     */
    public function generateSummaryOptions(UserDocument $resume, ?JobPosting $jobPosting = null): array
    {
        try {
            $context = [
                'resume_data' => $resume->extracted_data,
                'current_summary' => $resume->extracted_data['summary'] ?? '',
                'analysis_type' => 'summary_generation',
            ];

            if ($jobPosting) {
                $context['job_posting'] = [
                    'title' => $jobPosting->title,
                    'company' => $jobPosting->company,
                    'requirements' => $jobPosting->requirements,
                ];
            }

            $summaries = $this->aiService->analyzeResume($context);

            return $summaries;
        } catch (\Exception $e) {
            Log::error('Failed to generate summary options', [
                'resume_id' => $resume->id,
                'job_posting_id' => $jobPosting?->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'summary_options' => [],
                'tips' => [],
            ];
        }
    }

    /**
     * Analyze resume for common mistakes and issues
     */
    public function findCommonMistakes(UserDocument $resume): array
    {
        try {
            $context = [
                'resume_content' => $resume->content,
                'resume_data' => $resume->extracted_data,
                'analysis_type' => 'mistake_detection',
            ];

            $mistakes = $this->aiService->analyzeResume($context);

            return $mistakes;
        } catch (\Exception $e) {
            Log::error('Failed to find common mistakes', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'mistakes' => [],
                'severity_levels' => [],
                'fixes' => [],
            ];
        }
    }

    /**
     * Generate achievement bullet points
     */
    public function generateAchievementBullets(array $experienceData, ?JobPosting $jobPosting = null): array
    {
        try {
            $context = [
                'experience_data' => $experienceData,
                'analysis_type' => 'achievement_bullets',
            ];

            if ($jobPosting) {
                $context['target_role'] = [
                    'title' => $jobPosting->title,
                    'requirements' => $jobPosting->requirements,
                ];
            }

            $bullets = $this->aiService->analyzeResume($context);

            return $bullets;
        } catch (\Exception $e) {
            Log::error('Failed to generate achievement bullets', [
                'experience_data' => $experienceData,
                'error' => $e->getMessage(),
            ]);

            return [
                'bullet_points' => [],
                'tips' => [],
            ];
        }
    }
}
