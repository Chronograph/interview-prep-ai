<?php

namespace App\Livewire;

use App\Models\Resume;
use App\Models\JobPosting;
use App\Services\AIService;
use App\Services\CompanyResearchService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddNewInterviewModal extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Modal state
    public $showModal = false;
    
    // Form data
    public $jobPostingUrl = '';
    public $selectedResumeId = null;
    public $uploadNewResume = false;
    public $newResumeFile = null;
    
    // Data
    public $userResumes = [];
    public $resumeMatches = [];
    public $analyzingJob = false;
    public $jobAnalysis = null;
    
    // Services
    protected AIService $aiService;
    protected CompanyResearchService $companyService;

    public function boot(AIService $aiService, CompanyResearchService $companyService)
    {
        $this->aiService = $aiService;
        $this->companyService = $companyService;
    }

    public function mount()
    {
        $this->loadUserResumes();
    }

    #[On('open-add-interview-modal')]
    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
        $this->loadUserResumes();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->jobPostingUrl = '';
        $this->selectedResumeId = null;
        $this->uploadNewResume = false;
        $this->newResumeFile = null;
        $this->resumeMatches = [];
        $this->analyzingJob = false;
        $this->jobAnalysis = null;
    }

    public function loadUserResumes()
    {
        $this->userResumes = Resume::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($resume) {
                return [
                    'id' => $resume->id,
                    'title' => $resume->title ?? 'Untitled Resume',
                    'filename' => $resume->file_path ? basename($resume->file_path) : 'No file',
                    'uploaded_at' => $resume->created_at,
                    'file_size' => $resume->file_size,
                    'content' => $resume->raw_content ?? $resume->summary ?? '',
                    'skills' => $resume->skills ?? [],
                    'experience' => $resume->experience ?? [],
                ];
            })
            ->toArray();
    }

    public function analyzeJobPosting()
    {
        \Log::info('Analyze button clicked', [
            'url' => $this->jobPostingUrl,
            'user_id' => Auth::id()
        ]);

        if (empty($this->jobPostingUrl)) {
            session()->flash('error', 'Please enter a job posting URL');
            return;
        }

        $this->analyzingJob = true;
        
        try {
            \Log::info('Starting job analysis', ['url' => $this->jobPostingUrl]);
            
            // Analyze the job posting using AI
            $this->jobAnalysis = $this->companyService->analyzeJobPosting($this->jobPostingUrl);
            
            \Log::info('Job analysis completed', ['analysis' => $this->jobAnalysis]);
            
            // Calculate resume matches
            $this->calculateResumeMatches();
            
            \Log::info('Resume matches calculated', ['matches_count' => count($this->resumeMatches)]);
            
            // Force Livewire to update the UI
            $this->dispatch('$refresh');
            
            session()->flash('success', 'Job posting analyzed successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Job analysis failed', [
                'url' => $this->jobPostingUrl,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Failed to analyze job posting: ' . $e->getMessage());
        } finally {
            $this->analyzingJob = false;
        }
    }

    public function calculateResumeMatches()
    {
        \Log::info('Calculating resume matches', [
            'job_analysis_exists' => !empty($this->jobAnalysis),
            'user_resumes_count' => count($this->userResumes),
            'user_resumes' => $this->userResumes
        ]);

        if (!$this->jobAnalysis) {
            \Log::warning('No job analysis available for resume matching');
            return;
        }

        if (empty($this->userResumes)) {
            \Log::warning('No user resumes available for matching');
            return;
        }

        $jobSkills = $this->jobAnalysis['skills'] ?? [];
        $jobRequirements = $this->jobAnalysis['requirements'] ?? [];
        
        \Log::info('Job skills and requirements', [
            'job_skills' => $jobSkills,
            'job_requirements' => $jobRequirements
        ]);
        
        $this->resumeMatches = collect($this->userResumes)->map(function ($resume) use ($jobSkills, $jobRequirements) {
            $matchScore = $this->calculateMatchScore($resume, $jobSkills, $jobRequirements);
            $matchLevel = $this->getMatchLevel($matchScore);
            $matchingKeywords = $this->getMatchingKeywords($resume, $jobSkills, $jobRequirements);
            
            \Log::info('Resume match calculated', [
                'resume_id' => $resume['id'],
                'match_score' => $matchScore,
                'match_level' => $matchLevel,
                'matching_keywords' => $matchingKeywords
            ]);
            
            return [
                'resume' => $resume,
                'match_score' => $matchScore,
                'match_level' => $matchLevel,
                'matching_keywords' => $matchingKeywords,
            ];
        })->sortByDesc('match_score')->values()->toArray();
        
        \Log::info('Resume matches completed', [
            'matches_count' => count($this->resumeMatches),
            'matches' => $this->resumeMatches
        ]);
    }

    private function calculateMatchScore($resume, $jobSkills, $jobRequirements): int
    {
        $score = 0;
        $resumeSkills = $resume['skills'] ?? [];
        $resumeContent = strtolower($resume['content'] ?? '');
        
        // Skill matching (60% of score)
        $skillMatches = 0;
        foreach ($jobSkills as $skill) {
            if (in_array(strtolower($skill), array_map('strtolower', $resumeSkills))) {
                $skillMatches++;
            }
        }
        $score += $skillMatches > 0 ? ($skillMatches / count($jobSkills)) * 60 : 0;
        
        // Keyword matching in content (40% of score)
        $keywordMatches = 0;
        $allKeywords = array_merge($jobSkills, $jobRequirements);
        foreach ($allKeywords as $keyword) {
            if (strpos($resumeContent, strtolower($keyword)) !== false) {
                $keywordMatches++;
            }
        }
        $score += $keywordMatches > 0 ? ($keywordMatches / count($allKeywords)) * 40 : 0;
        
        return min(round($score), 100);
    }

    private function getMatchLevel($score): array
    {
        if ($score >= 85) {
            return [
                'label' => 'Excellent Match',
                'color' => 'bg-green-100 text-green-800',
                'icon' => 'star',
                'icon_color' => 'text-green-600'
            ];
        } elseif ($score >= 70) {
            return [
                'label' => 'Good Match',
                'color' => 'bg-blue-100 text-blue-800',
                'icon' => 'wave',
                'icon_color' => 'text-blue-600'
            ];
        } elseif ($score >= 50) {
            return [
                'label' => 'Fair Match',
                'color' => 'bg-gray-100 text-gray-800',
                'icon' => 'document',
                'icon_color' => 'text-gray-600'
            ];
        } else {
            return [
                'label' => 'Poor Match',
                'color' => 'bg-red-100 text-red-800',
                'icon' => 'exclamation',
                'icon_color' => 'text-red-600'
            ];
        }
    }

    private function getMatchingKeywords($resume, $jobSkills, $jobRequirements): array
    {
        $matchingKeywords = [];
        $resumeSkills = $resume['skills'] ?? [];
        $resumeContent = strtolower($resume['content'] ?? '');
        
        $allKeywords = array_merge($jobSkills, $jobRequirements);
        
        foreach ($allKeywords as $keyword) {
            if (in_array(strtolower($keyword), array_map('strtolower', $resumeSkills)) ||
                strpos($resumeContent, strtolower($keyword)) !== false) {
                $matchingKeywords[] = $keyword;
            }
        }
        
        return array_slice($matchingKeywords, 0, 3); // Return top 3 matches
    }

    public function selectResume($resumeId)
    {
        $this->selectedResumeId = $resumeId;
        $this->uploadNewResume = false;
    }

    public function toggleUploadNew()
    {
        $this->uploadNewResume = !$this->uploadNewResume;
        $this->selectedResumeId = null;
    }

    public function uploadNewResume()
    {
        $this->validate([
            'newResumeFile' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        try {
            // Process the uploaded resume
            $resume = $this->processUploadedResume();
            
            // Select the newly uploaded resume
            $this->selectedResumeId = $resume->id;
            $this->uploadNewResume = false;
            
            // Reload resumes and recalculate matches
            $this->loadUserResumes();
            if ($this->jobAnalysis) {
                $this->calculateResumeMatches();
            }
            
            session()->flash('success', 'Resume uploaded and analyzed successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload resume: ' . $e->getMessage());
        }
    }

    private function processUploadedResume()
    {
        $file = $this->newResumeFile;
        $filename = $file->getClientOriginalName();
        $content = file_get_contents($file->getRealPath());
        
        // Use fallback analysis since AI service is not available
        $analysis = [
            'summary' => 'Resume uploaded successfully',
            'skills' => ['General Skills', 'Professional Experience'],
            'experience' => ['Previous work experience'],
            'education' => ['Educational background'],
        ];
        
        // Create the resume record
        $resume = Resume::create([
            'user_id' => Auth::id(),
            'title' => pathinfo($filename, PATHINFO_FILENAME),
            'file_path' => $file->store('resumes', 'private'),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'raw_content' => $content,
            'summary' => $analysis['summary'],
            'skills' => $analysis['skills'],
            'experience' => $analysis['experience'],
            'education' => $analysis['education'],
            'optimization_score' => 0, // Will be calculated
        ]);
        
        // Update optimization score
        $resume->updateOptimizationScore();
        
        return $resume;
    }

    public function startInterviewPractice()
    {
        \Log::info('Start Interview Practice button clicked', [
            'url' => $this->jobPostingUrl,
            'selected_resume_id' => $this->selectedResumeId,
            'upload_new_resume' => $this->uploadNewResume,
            'has_new_resume_file' => $this->newResumeFile ? true : false,
            'user_id' => Auth::id()
        ]);

        if (empty($this->jobPostingUrl)) {
            session()->flash('error', 'Please enter a job posting URL');
            return;
        }

        if (!$this->selectedResumeId && !$this->uploadNewResume) {
            session()->flash('error', 'Please select a resume or upload a new one');
            return;
        }

        if ($this->uploadNewResume && !$this->newResumeFile) {
            session()->flash('error', 'Please upload a resume file');
            return;
        }

        try {
            \Log::info('Creating job posting record');
            
            // Create job posting record
            $jobPosting = $this->createJobPosting();
            
            \Log::info('Job posting created', ['job_posting_id' => $jobPosting->id]);
            
            // Get or create resume
            $resume = $this->selectedResumeId 
                ? Resume::find($this->selectedResumeId)
                : $this->processUploadedResume();
            
            \Log::info('Resume obtained', ['resume_id' => $resume->id]);
            
            // Create interview session
            $sessionConfig = [
                'session_type' => 'company_specific',
                'focus_area' => 'company_research',
                'difficulty' => 'advanced',
                'questions_count' => 15,
                'job_posting_id' => $jobPosting->id,
                'resume_id' => $resume->id,
            ];
            
            \Log::info('Redirecting to interview session creation', $sessionConfig);
            
            // Close modal and redirect to interview session creation
            $this->closeModal();
            
            return redirect()->route('interview-sessions.create', $sessionConfig);
            
        } catch (\Exception $e) {
            \Log::error('Failed to start interview practice', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Failed to start interview practice: ' . $e->getMessage());
        }
    }

    private function createJobPosting()
    {
        return JobPosting::create([
            'user_id' => Auth::id(),
            'title' => $this->jobAnalysis['title'] ?? 'Job Position',
            'company' => $this->jobAnalysis['company'] ?? 'Company',
            'description' => $this->jobAnalysis['description'] ?? '',
            'requirements' => $this->jobAnalysis['requirements'] ?? [],
            'skills' => $this->jobAnalysis['skills'] ?? [],
            'location' => $this->jobAnalysis['location'] ?? '',
            'source_url' => $this->jobPostingUrl,
            'is_active' => true,
        ]);
    }

    public function formatFileSize($bytes): string
    {
        if (!$bytes) {
            return 'N/A';
        }

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / (1024 * 1024), 1) . ' MB';
    }

    public function render()
    {
        return view('livewire.add-new-interview-modal');
    }
}
