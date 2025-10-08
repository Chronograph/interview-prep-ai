<div class="min-h-screen bg-gray-50">
    <!-- Welcome Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}!</h1>
                    <p class="text-gray-600 mt-1">Ready to level up your interview skills? Let's see how you're progressing.</p>
                </div>
                <div class="flex space-x-4">
                <x-button
                    wire:click="startInterview"
                    primary
                    size="lg"
                    icon="video-camera"
                >
                    Start Practice Session
                </x-button>
            </div>
        </div>

            <!-- Summary Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                <!-- Total Sessions Card -->
                <x-card>
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <x-icon name="video-camera" class="w-6 h-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalSessions }}</p>
                            <p class="text-sm text-green-600">+{{ $sessionsThisWeek }} this week</p>
                        </div>
                    </div>
                </x-card>

                <!-- Average Score Card -->
                <x-card>
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <x-icon name="check-circle" class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Avg. Score</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $averageScore }}/10</p>
                            <p class="text-sm {{ $scoreImprovement >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $scoreImprovement >= 0 ? '+' : '' }}{{ $scoreImprovement }}% improvement
                            </p>
                        </div>
                    </div>
                </x-card>

                <!-- Interview Ready Card -->
                <x-card>
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <x-icon name="building-office" class="w-6 h-6 text-purple-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Interview Ready</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $interviewReadyCount }} Companies</p>
                            <p class="text-sm text-gray-500">Ready to interview</p>
                        </div>
                    </div>
                </x-card>

                <!-- Need Practice Card -->
                <x-card>
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <x-icon name="exclamation-triangle" class="w-6 h-6 text-orange-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Need Practice</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $needPracticeCount }} Skills</p>
                            <p class="text-sm text-orange-600">requiring focus</p>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Practice Sessions -->
            <div class="lg:col-span-2">
                <!-- Tab Navigation -->
                <div class="flex space-x-1 mb-6">
                    @if($activeTab === 'recent')
                        <x-button wire:click="setActiveTab('recent')" primary size="sm">
                            Recent Practice
                            <x-badge size="xs" class="bg-white/20 text-xs px-2 py-1 rounded-full" class="ml-2">24</x-badge>
                        </x-button>
                    @else
                        <x-button wire:click="setActiveTab('recent')" secondary size="sm">
                            Recent Practice
                            <x-badge size="xs" class="bg-white/20 text-xs px-2 py-1 rounded-full" class="ml-2">24</x-badge>
                        </x-button>
                    @endif

                    @if($activeTab === 'upcoming')
                        <x-button wire:click="setActiveTab('upcoming')" primary size="sm">
                            Upcoming Interviews
                            <x-badge size="xs" class="bg-white/20 text-xs px-2 py-1 rounded-full" class="ml-2">3</x-badge>
                        </x-button>
                    @else
                        <x-button wire:click="setActiveTab('upcoming')" secondary size="sm">
                            Upcoming Interviews
                            <x-badge size="xs" class="bg-white/20 text-xs px-2 py-1 rounded-full" class="ml-2">3</x-badge>
                        </x-button>
                    @endif
            </div>

                @if($activeTab === 'recent')
                    <!-- Recent Practice Sessions -->
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Practice Sessions</h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <x-button size="xs" class="text-gray-500 hover:text-gray-700">Date</x-button>
                                <x-button size="xs" class="text-gray-500 hover:text-gray-700">Readiness</x-button>
                                <x-link href="#" variant="primary" size="sm">View All</x-link>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($practiceSessions as $session)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-gray-700">{{ $session['company_initial'] }}</span>
                                        </div>
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <h4 class="text-sm font-semibold text-gray-900">{{ $session['role'] }}</h4>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $this->getDifficultyColor($session['difficulty']) }}">
                                                    {{ $session['difficulty'] }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $session['company'] }} • {{ $session['time_ago'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $session['questions_answered'] }} questions</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        @if($session['score'])
                                            <span class="text-sm font-semibold {{ $session['score'] >= 7 ? 'text-green-600' : ($session['score'] >= 5 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $session['score'] }}/10
                                            </span>
                                        @endif
                                        <x-button size="xs" icon="play" class="text-blue-600 hover:text-blue-700">
                                            {{ $session['status'] === 'completed' ? 'Retake' : 'Continue' }}
                                        </x-button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Upcoming Interviews -->
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming Interviews</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($upcomingInterviews as $interview)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-blue-700">{{ substr($interview['company'], 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $interview['role'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $interview['company'] }} • {{ $interview['type'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $interview['date'] }} at {{ $interview['time'] }}</p>
                                        </div>
                                    </div>
                                    <x-button size="xs" icon="play" class="text-blue-600 hover:text-blue-700">
                                        Prepare
                                    </x-button>
                                    </div>
                                @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Actions</h3>
                    <p class="text-sm text-gray-600 mb-4">Get started with your interview preparation</p>
                    <div class="space-y-3">
                        <x-button class="w-full" primary icon="play">
                            Start Practice Session
                        </x-button>
                        <x-button class="w-full" secondary icon="plus">
                            Add Interview
                        </x-button>
                        <x-button class="w-full" secondary icon="magnifying-glass">
                            Find Jobs
                        </x-button>
                        <x-button class="w-full" secondary icon="cog-6-tooth">
                            Set Goals
                        </x-button>
                        <x-button class="w-full" secondary icon="book-open">
                            Study Resources
                        </x-button>
                        <x-button class="w-full" secondary icon="document-text">
                            Resume Review
                        </x-button>
                    </div>
                </div>

                <!-- Audio & Visual Setup -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Audio & Visual Setup</h3>
                    <div class="space-y-4">
                        @php
                            $setupMetrics = [
                                [
                                    'label' => 'Video Quality',
                                    'value' => $audioVisualSetup['video_quality'] ?? 0,
                                    'color' => 'bg-green-500',
                                ],
                                [
                                    'label' => 'Audio Quality',
                                    'value' => $audioVisualSetup['audio_quality'] ?? 0,
                                    'color' => 'bg-blue-500',
                                ],
                                [
                                    'label' => 'Video Background Quality',
                                    'value' => $audioVisualSetup['background_quality'] ?? 0,
                                    'color' => 'bg-blue-500',
                                ],
                                [
                                    'label' => 'Distracting Elements',
                                    'value' => $audioVisualSetup['distracting_elements'] ?? 0,
                                    'color' => 'bg-red-500',
                                ],
                            ];
                        @endphp
                        @foreach($setupMetrics as $metric)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">{{ $metric['label'] }}</span>
                                    <span class="text-gray-900">{{ $metric['value'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="{{ $metric['color'] }} h-2 rounded-full" style="width: {{ $metric['value'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-right">
                        <x-link href="#" variant="primary" size="sm">View All Tips</x-link>
                    </div>
                </div>
                    </div>
                </div>

        <!-- Personalized Recommendations -->
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Personalized Recommendations</h3>
            <p class="text-gray-600 mb-6">AI-powered suggestions based on your recent performance</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recommendations as $recommendation)
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if($recommendation['icon'] === 'star')
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @elseif($recommendation['icon'] === 'microphone')
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($recommendation['icon'] === 'building')
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                </svg>
                                    @endif
                                </div>
                                <span class="text-sm font-medium {{ $this->getPriorityColor($recommendation['priority']) }}">
                                    {{ ucfirst($recommendation['priority']) }}
                                </span>
                            </div>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ $recommendation['title'] }}</h4>
                        <p class="text-xs text-gray-600 mb-3">{{ $recommendation['description'] }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $recommendation['duration'] }} • {{ $recommendation['category'] }}</span>
                            <x-button size="xs" class="text-blue-600 hover:text-blue-700">
                                Start {{ str_replace('Practice ', '', $recommendation['title']) }} →
                            </x-button>
                        </div>
                    </div>
                                        @endforeach
                                    </div>
                                </div>

        <!-- Keep up the momentum! -->
        <div class="mt-8 bg-green-50 rounded-lg border border-green-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Keep up the momentum!</h3>
                    <p class="text-gray-600 mt-1">You've improved 18% this month. Focus on these areas to reach interview-ready status for all companies.</p>
                </div>
                <x-button success icon="play">
                    Start Focused Practice
                </x-button>
            </div>
        </div>

        <!-- Skill Progress Over Time -->
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Skill Progress Over Time</h3>
            <p class="text-gray-600 mb-6">Track your improvement across key interview skills</p>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <!-- Simple chart representation -->
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                        <p class="text-gray-500">Skill Progress Chart</p>
                        <p class="text-sm text-gray-400">Overall Score, Product Thinking, Communication, Leadership</p>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-6 mt-4">
                    @php
                        $skills = [
                            ['color' => 'bg-gray-600', 'label' => 'Overall Score'],
                            ['color' => 'bg-purple-600', 'label' => 'Product Thinking'],
                            ['color' => 'bg-green-600', 'label' => 'Communication'],
                            ['color' => 'bg-orange-600', 'label' => 'Leadership'],
                        ];
                    @endphp
                    @foreach($skills as $skill)
                        <div class="flex items-center">
                            <div class="w-3 h-3 {{ $skill['color'] }} rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">{{ $skill['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                    <h3 class="text-xl font-bold text-gray-900">Recommended Jobs for You</h3>
                    <p class="text-gray-600">Based on your practice sessions and skill development</p>
                </div>
                <x-link href="#" variant="primary" size="sm">View All Jobs</x-link>
                                        </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($recommendedJobs as $job)
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $job['title'] }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                        {{ $job['match'] }}% match
                                    </span>
                                        </div>
                                <p class="text-gray-600">{{ $job['company'] }} • {{ $job['location'] }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $job['posted'] }} • {{ $job['applicants'] }} applicants</p>
                                        </div>
                                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-lg font-semibold text-gray-900">{{ $job['salary'] }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-sm text-gray-600">Your Practice Score:</span>
                                    <span class="text-sm font-semibold text-gray-900 ml-2">{{ $job['practice_score'] }}/10</span>
                                    <svg class="w-4 h-4 text-green-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $this->getReadinessColor($job['readiness']) }}">
                                {{ $job['readiness'] }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2 mb-2">
                                <span class="text-xs text-gray-600">Strong Skills:</span>
                                @foreach($job['strong_skills'] as $skill)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ $skill }}</span>
                                @endforeach
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs text-gray-600">Practice Areas:</span>
                                @foreach($job['practice_areas'] as $area)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">{{ $area }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <x-button class="flex-1" primary>
                                Apply Now
                            </x-button>
                            <x-button class="flex-1" secondary icon="play">
                                Practice
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Product Role Insights -->
        <div class="mt-8 bg-purple-50 rounded-lg border border-purple-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Product Role Insights</h3>
                        <p class="text-gray-600 mt-1">{{ $productInsights['message'] }}</p>
                    </div>
                </div>
                <x-button primary class="bg-purple-600 hover:bg-purple-700 text-white">
                    {{ $productInsights['action'] }}
                </x-button>
            </div>
        </div>
    </div>

    <!-- Interview Interface Modal -->
    @if($showInterviewInterface)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gradient-to-br from-purple-900/50 to-blue-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-white/20">
                    <livewire:interview-interface
                        :job-posting-id="$selectedJobPosting?->id"
                        wire:key="interview-interface-{{ $selectedJobPosting?->id }}"
                    />
                </div>
            </div>
        </div>
    @endif
</div>
