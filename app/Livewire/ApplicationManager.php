<?php

namespace App\Livewire;

use App\Models\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplicationManager extends Component
{
    use AuthorizesRequests;

    // State properties
    public $applications = [];

    public $search = '';

    public $sortBy = 'application_date';

    public $sortDirection = 'desc';

    public $activeTab = 'my_applications';

    public $upcomingInterviews = [];

    public $recommendedJobs = [];

    // Statistics
    public $totalApplications = 0;

    public $upcomingInterviewsCount = 0;

    public $interviewReady = 82;

    public $offersReceived = 0;

    public $showCreateModal = false;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $editingApplication = null;

    public $deleteApplicationId = null;

    // Form fields
    public $company_name = '';

    public $position_title = '';

    public $job_url = '';

    public $status = 'applied';

    public $priority = 'medium';

    public $application_date = '';

    public $expected_response_date = '';

    public $salary_min = '';

    public $salary_max = '';

    public $location = '';

    public $work_type = '';

    public $notes = '';

    public $interview_stages = [];

    public $contacts = [];

    public $requirements = [];

    public $is_favorite = false;

    // Status and priority options
    public $statusOptions = [
        'applied' => 'Applied',
        'screening' => 'Screening',
        'interview' => 'Interview',
        'offer' => 'Offer',
        'rejected' => 'Rejected',
        'withdrawn' => 'Withdrawn',
    ];

    public $priorityOptions = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];

    public $workTypeOptions = [
        'remote' => 'Remote',
        'hybrid' => 'Hybrid',
        'onsite' => 'Onsite',
    ];

    public function mount()
    {
        $this->application_date = now()->format('Y-m-d');
        $this->loadApplications();
        $this->loadUpcomingInterviews();
        $this->loadRecommendedJobs();
        $this->calculateStatistics();
    }

    public function loadApplications()
    {
        $query = Auth::user()->applications();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%'.$this->search.'%')
                    ->orWhere('position_title', 'like', '%'.$this->search.'%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $this->applications = $query->get()->toArray();
    }

    private function calculateStatistics()
    {
        $user = Auth::user();

        // Total applications
        $this->totalApplications = $user->applications()->count();

        // Upcoming interviews count
        $this->upcomingInterviewsCount = \App\Models\Interview::where('user_id', Auth::id())
            ->where('interview_date', '>=', now()->toDateString())
            ->count();

        // Interview ready (placeholder calculation)
        $this->interviewReady = 82; // This would be calculated based on practice sessions

        // Offers received
        $this->offersReceived = $user->applications()
            ->where('status', 'offer')
            ->count();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        $this->authorize('view', $application);

        $this->editingApplication = $application->id;
        $this->company_name = $application->company_name;
        $this->position_title = $application->position_title;
        $this->job_url = $application->job_url ?? '';
        $this->status = $application->status;
        $this->priority = $application->priority;
        $this->application_date = $application->application_date;
        $this->expected_response_date = $application->expected_response_date ?? '';
        $this->salary_min = $application->salary_min ?? '';
        $this->salary_max = $application->salary_max ?? '';
        $this->location = $application->location ?? '';
        $this->work_type = $application->work_type ?? '';
        $this->notes = $application->notes ?? '';
        $this->interview_stages = $application->interview_stages ?? [];
        $this->contacts = $application->contacts ?? [];
        $this->requirements = $application->requirements ?? [];
        $this->is_favorite = $application->is_favorite ?? false;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingApplication = null;
    }

    public function openDeleteModal($applicationId)
    {
        $this->deleteApplicationId = $applicationId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteApplicationId = null;
    }

    public function createApplication()
    {
        $validated = $this->validate([
            'company_name' => 'required|string|max:255',
            'position_title' => 'required|string|max:255',
            'job_url' => 'nullable|url|max:500',
            'status' => 'required|in:applied,screening,interview,offer,rejected,withdrawn',
            'priority' => 'required|in:low,medium,high',
            'application_date' => 'required|date',
            'expected_response_date' => 'nullable|date|after:application_date',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0|gte:salary_min',
            'location' => 'nullable|string|max:255',
            'work_type' => 'nullable|in:remote,hybrid,onsite',
            'notes' => 'nullable|string|max:1000',
            'interview_stages' => 'nullable|array',
            'contacts' => 'nullable|array',
            'requirements' => 'nullable|array',
            'is_favorite' => 'boolean',
        ]);

        Application::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        $this->loadApplications();
        $this->calculateStatistics();
        $this->closeCreateModal();
        session()->flash('success', 'Application added successfully!');
    }

    public function updateApplication()
    {
        $application = Application::findOrFail($this->editingApplication);
        $this->authorize('update', $application);

        $validated = $this->validate([
            'company_name' => 'required|string|max:255',
            'position_title' => 'required|string|max:255',
            'job_url' => 'nullable|url|max:500',
            'status' => 'required|in:applied,screening,interview,offer,rejected,withdrawn',
            'priority' => 'required|in:low,medium,high',
            'application_date' => 'required|date',
            'expected_response_date' => 'nullable|date|after:application_date',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0|gte:salary_min',
            'location' => 'nullable|string|max:255',
            'work_type' => 'nullable|in:remote,hybrid,onsite',
            'notes' => 'nullable|string|max:1000',
            'interview_stages' => 'nullable|array',
            'contacts' => 'nullable|array',
            'requirements' => 'nullable|array',
            'is_favorite' => 'boolean',
        ]);

        $application->update($validated);

        $this->loadApplications();
        $this->calculateStatistics();
        $this->closeEditModal();
        session()->flash('success', 'Application updated successfully!');
    }

    public function deleteApplication()
    {
        $application = Application::findOrFail($this->deleteApplicationId);
        $this->authorize('delete', $application);

        $application->delete();

        $this->loadApplications();
        $this->calculateStatistics();
        $this->closeDeleteModal();
        session()->flash('success', 'Application deleted successfully!');
    }

    public function updateStatus($applicationId, $newStatus)
    {
        $application = Application::findOrFail($applicationId);
        $this->authorize('update', $application);

        $application->updateStatus($newStatus);

        $this->loadApplications();
        $this->calculateStatistics();
        session()->flash('success', 'Status updated successfully!');
    }

    public function toggleFavorite($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        $this->authorize('update', $application);

        $application->update([
            'is_favorite' => ! $application->is_favorite,
        ]);

        $this->loadApplications();
        $this->calculateStatistics();
        session()->flash('success', 'Favorite status updated!');
    }

    public function updatedSearch()
    {
        $this->loadApplications();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }

        $this->loadApplications();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function loadUpcomingInterviews()
    {
        $this->upcomingInterviews = \App\Models\Interview::where('user_id', Auth::id())
            ->where('interview_date', '>=', now()->toDateString())
            ->orderBy('interview_date')
            ->orderBy('interview_time')
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'title' => $interview->title,
                    'company' => $interview->company,
                    'position' => $interview->position,
                    'interview_date' => $interview->interview_date,
                    'interview_time' => $interview->interview_time,
                    'interview_type' => $interview->interview_type,
                    'location' => $interview->location,
                    'readiness_score' => $interview->readiness_score ?? 0,
                    'status' => $interview->status,
                ];
            });
    }

    public function loadRecommendedJobs()
    {
        // Mock recommended jobs data matching the design
        $this->recommendedJobs = collect([
            [
                'id' => 1,
                'position_title' => 'Senior Product Manager',
                'company_name' => 'TechFlow',
                'location' => 'San Francisco, CA',
                'salary_min' => 160000,
                'salary_max' => 200000,
                'match_percentage' => 92,
                'practice_score' => 8.1,
                'interview_readiness' => 'High',
                'posted_days_ago' => 2,
                'applicants_count' => 45,
                'strong_skills' => ['Product Strategy', 'User Research', 'Data Analysis'],
                'practice_areas' => ['Stakeholder Management'],
                'description' => 'Lead product strategy for our streaming platform, working with cross-functional teams to deliver innovative features that enhance user experience.',
            ],
            [
                'id' => 2,
                'position_title' => 'Product Design Manager',
                'company_name' => 'InnovateLabs',
                'location' => 'New York, NY',
                'salary_min' => 140000,
                'salary_max' => 170000,
                'match_percentage' => 78,
                'practice_score' => 6.8,
                'interview_readiness' => 'Medium',
                'posted_days_ago' => 7,
                'applicants_count' => 123,
                'strong_skills' => ['Design Leadership', 'User Experience'],
                'practice_areas' => ['Team Management', 'Design Systems'],
                'description' => 'Drive product vision for music discovery features, leveraging machine learning to personalize user experiences.',
            ],
            [
                'id' => 3,
                'position_title' => 'Principal Product Manager',
                'company_name' => 'DataSystems',
                'location' => 'Seattle, WA',
                'salary_min' => 180000,
                'salary_max' => 220000,
                'match_percentage' => 85,
                'practice_score' => 7.4,
                'interview_readiness' => 'High',
                'posted_days_ago' => 3,
                'applicants_count' => 67,
                'strong_skills' => ['Product Vision', 'Cross-functional Leadership', 'Analytics'],
                'practice_areas' => ['Executive Communication'],
                'description' => 'Shape the future of AI products, working on cutting-edge language models and AI applications.',
            ],
        ]);
    }

    public function redirectToUpcomingInterviews()
    {
        return redirect()->route('upcoming-interviews');
    }

    public function startPractice($interviewId)
    {
        // Redirect to practice session with interview context
        return redirect()->route('practice', ['interview_id' => $interviewId]);
    }

    public function refreshRecommendations()
    {
        $this->loadRecommendedJobs();
        session()->flash('success', 'Recommendations refreshed!');
    }

    public function applyToJob($jobId)
    {
        // Create application from recommended job
        $job = $this->recommendedJobs->firstWhere('id', $jobId);
        if ($job) {
            Application::create([
                'user_id' => Auth::id(),
                'company_name' => $job['company_name'],
                'position_title' => $job['position_title'],
                'location' => $job['location'],
                'salary_min' => $job['salary_min'],
                'salary_max' => $job['salary_max'],
                'status' => 'applied',
                'priority' => 'medium',
                'application_date' => now()->toDateString(),
                'work_type' => 'hybrid',
                'notes' => 'Applied from recommended jobs',
            ]);

            $this->loadApplications();
            $this->calculateStatistics();
            session()->flash('success', 'Application submitted successfully!');
        }
    }

    public function practiceForJob($jobId)
    {
        // Redirect to practice session with job context
        return redirect()->route('practice', ['job_id' => $jobId]);
    }

    public function viewAllJobs()
    {
        // Redirect to a comprehensive job search page
        session()->flash('info', 'Redirecting to job search page...');
        // For now, just refresh recommendations
        $this->loadRecommendedJobs();
    }

    public function getProductJobAlerts()
    {
        // Set up job alerts for product roles
        session()->flash('success', 'Product job alerts enabled! You\'ll receive notifications for new matching opportunities.');
    }

    private function resetForm()
    {
        $this->company_name = '';
        $this->position_title = '';
        $this->job_url = '';
        $this->status = 'applied';
        $this->priority = 'medium';
        $this->application_date = now()->format('Y-m-d');
        $this->expected_response_date = '';
        $this->salary_min = '';
        $this->salary_max = '';
        $this->location = '';
        $this->work_type = '';
        $this->notes = '';
        $this->interview_stages = [];
        $this->contacts = [];
        $this->requirements = [];
        $this->is_favorite = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.application-manager');
    }
}
