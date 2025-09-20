<div>
    <!-- Interview Interface Modal -->
    @if($showInterviewInterface)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gradient-to-br from-purple-900/50 to-blue-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-white/20">
                    <livewire:interview-interface 
                        :job-posting-id="$selectedJobPostingId" 
                        wire:key="interview-interface-{{ $selectedJobPostingId }}"
                    />
                </div>
            </div>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Dashboard</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Welcome back! Here's your interview preparation overview.</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <button 
                wire:click="refreshData" 
                class="inline-flex items-center px-6 py-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl font-semibold text-sm text-gray-700 dark:text-gray-300 shadow-lg hover:bg-white/90 dark:hover:bg-gray-700/90 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <button 
                wire:click="startInterview" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:from-purple-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Start Interview
            </button>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8">
        <nav class="flex space-x-2 p-2 bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-lg" aria-label="Tabs">
            <button 
                wire:click="setActiveTab('overview')"
                class="{{ $activeTab === 'overview' ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50' }} px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-200 transform hover:scale-105"
            >
                Overview
            </button>
            <button 
                wire:click="setActiveTab('job-postings')"
                class="{{ $activeTab === 'job-postings' ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50' }} px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-200 transform hover:scale-105"
            >
                Job Postings
            </button>
            <button 
                wire:click="setActiveTab('resumes')"
                class="{{ $activeTab === 'resumes' ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50' }} px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-200 transform hover:scale-105"
            >
                Resumes
            </button>
        </nav>
    </div>

    <!-- Overview Tab -->
    @if($activeTab === 'overview')
        <div class="space-y-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Interviews</dt>
                                    <dd class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">{{ $stats['total_interviews'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Completion Rate</dt>
                                    <dd class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">{{ $this->completionRate }}%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Average Score</dt>
                                    <dd class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                        {{ $stats['average_score'] ?? 0 }}%
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Job Postings</dt>
                                    <dd class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ $stats['total_job_postings'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Interviews -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50">
                    <div class="px-6 py-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Recent Interviews</h3>
                        </div>
                        @if(count($recent_interviews) > 0)
                            <div class="space-y-4">
                                @foreach($recent_interviews as $interview)
                                    <div class="flex items-center justify-between p-5 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/30 hover:bg-white/70 dark:hover:bg-gray-700/70 transition-all duration-200">
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $interview['job_posting']['title'] ?? 'General Interview' }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($interview['created_at'])->format('M j, Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if(isset($interview['overall_score']))
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg">
                                                    {{ $interview['overall_score'] }}%
                                                </span>
                                            @endif
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $interview['status'] === 'completed' ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' : 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white' }} shadow-lg">
                                                {{ $interview['status'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="p-4 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">No interviews yet</p>
                                <button 
                                    wire:click="startInterview" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:from-purple-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Start Your First Interview
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50">
                    <div class="px-6 py-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Quick Actions</h3>
                        </div>
                        <div class="space-y-4">
                            @if($this->recentJobPostings->count() > 0)
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-4">Practice with Recent Job Postings</h4>
                                    <div class="space-y-2">
                                        @foreach($this->recentJobPostings as $jobPosting)
                                            <button 
                                                wire:click="startInterview({{ $jobPosting['id'] }})"
                                                class="w-full text-left p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/50 dark:to-indigo-900/50 backdrop-blur-sm hover:from-blue-100 hover:to-indigo-100 dark:hover:from-blue-800/60 dark:hover:to-indigo-800/60 rounded-xl border border-blue-200/50 dark:border-blue-700/50 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                            >
                                                <div class="font-semibold text-blue-900 dark:text-blue-100 text-base">{{ $jobPosting['title'] ?? 'Untitled Position' }}</div>
                                                <div class="text-sm text-blue-700 dark:text-blue-300 mt-1">{{ $jobPosting['company'] ?? 'Unknown Company' }}</div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="border-t border-gray-200/50 dark:border-gray-600/50 pt-6">
                                <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-4">Manage Your Profile</h4>
                                <div class="space-y-2">
                                    <button 
                                        wire:click="setActiveTab('resumes')"
                                        class="w-full flex items-center p-4 text-gray-700 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-gray-700/60 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/30 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl mb-3"
                                    >
                                        <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg mr-4">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 text-left">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">Manage Resumes</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $stats['total_resumes'] }} resumes</div>
                                        </div>
                                    </button>
                                    <button 
                                        wire:click="setActiveTab('job-postings')"
                                        class="w-full flex items-center p-4 text-gray-700 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-gray-700/60 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/30 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                    >
                                        <div class="p-2 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mr-4">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 text-left">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">Add Job Postings</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $stats['total_job_postings'] }} postings</div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Job Postings Tab -->
    @if($activeTab === 'job-postings')
        <livewire:job-posting-manager 
            :job-postings="$job_postings"
            wire:key="job-postings-manager"
        />
    @endif

    <!-- Resumes Tab -->
    @if($activeTab === 'resumes')
        <livewire:resume-manager 
            :resumes="$resumes"
            wire:key="resume-manager"
        />
    @endif
</div>
