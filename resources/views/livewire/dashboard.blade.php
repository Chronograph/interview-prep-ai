<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        Welcome back, {{ $user->name }}! 👋
                    </h1>
                    <p class="text-lg text-gray-600">You're on track to ace your next interview. Keep up the great work!</p>
                </div>

            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sessions Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalSessions }}</p>
                        <p class="text-sm text-green-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                            </svg>
                            +{{ $sessionsThisWeek }} this week
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <x-icon name="video-camera" class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
            </div>

            <!-- Average Score Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Avg. Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $averageScore }}<span class="text-xl text-gray-500">/10</span></p>
                        @if($scoreImprovement != 0)
                            <p class="text-sm {{ $scoreImprovement >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1 flex items-center gap-1">
                                @if($scoreImprovement >= 0)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                    </svg>
                                    +{{ $scoreImprovement }}% improvement
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $scoreImprovement }}% change
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-500 mt-1">No change</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <x-icon name="check-circle" class="w-6 h-6 text-green-600" />
                    </div>
                </div>
            </div>

            <!-- Interview Ready Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Interview Ready</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $interviewReadyCount }}</p>
                        <p class="text-sm text-gray-500 mt-1">Companies</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <x-icon name="building-office" class="w-6 h-6 text-purple-600" />
                    </div>
                </div>
            </div>

            <!-- Need Practice Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Need Practice</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $needPracticeCount }}</p>
                        <p class="text-sm text-orange-600 mt-1">Skills requiring focus</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <x-icon name="exclamation-triangle" class="w-6 h-6 text-orange-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Practice Sessions & Interviews -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Navigation Tabs -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <button
                                wire:click="setActiveTab('recent')"
                                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'recent' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            >
                                <span class="flex items-center gap-2">
                                    <x-icon name="clock" class="w-4 h-4" />
                                    Recent Practice
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $activeTab === 'recent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $practiceSessions->count() }}
                                    </span>
                                </span>
                            </button>
                            <button
                                wire:click="setActiveTab('upcoming')"
                                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'upcoming' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            >
                                <span class="flex items-center gap-2">
                                    <x-icon name="calendar" class="w-4 h-4" />
                                    Upcoming Interviews
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $activeTab === 'upcoming' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $upcomingInterviews->count() }}
                                    </span>
                                </span>
                            </button>
                        </nav>
                    </div>

                    @if($activeTab === 'recent')
                        <!-- Recent Practice Sessions -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Practice Sessions</h3>
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All →</a>
                            </div>

                            <div class="space-y-4">
                                @forelse($practiceSessions as $session)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all duration-200">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                                <!-- Company Avatar -->
                                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg flex items-center justify-center font-semibold text-sm">
                                                    {{ $session['company_initial'] }}
                                                </div>

                                                <!-- Session Info -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h4 class="font-semibold text-gray-900 truncate">{{ $session['role'] }}</h4>
                                                        @php
                                                            $difficultyColors = [
                                                                'Hard' => 'bg-red-100 text-red-700',
                                                                'Medium' => 'bg-yellow-100 text-yellow-700',
                                                                'Easy' => 'bg-green-100 text-green-700',
                                                            ];
                                                            $difficultyColor = $difficultyColors[$session['difficulty']] ?? 'bg-gray-100 text-gray-700';
                                                        @endphp
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $difficultyColor }}">
                                                            {{ $session['difficulty'] }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600">{{ $session['company'] }} • {{ $session['time_ago'] }}</p>
                                                    <div class="flex items-center gap-3 mt-2">
                                                        <span class="text-xs text-gray-500">{{ $session['questions_answered'] }} questions</span>
                                                        @if($session['score'])
                                                            @php
                                                                $scoreColors = $session['score'] >= 7 ? 'bg-green-100 text-green-700' : ($session['score'] >= 5 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                                                            @endphp
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium {{ $scoreColors }}">
                                                                <x-icon name="star" class="w-3 h-3" solid />
                                                                {{ $session['score'] }}/10
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Button -->
                                            <div class="flex-shrink-0">
                                                <x-button primary sm icon="play">
                                                    {{ $session['status'] === 'completed' ? 'Retake' : 'Continue' }}
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                            <x-icon name="document-text" class="w-8 h-8 text-gray-400" />
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No practice sessions yet</h3>
                                        <p class="text-gray-600 mb-6">Start your first practice session to begin improving your interview skills</p>
                                        <x-button primary icon="play" wire:click="startPractice">
                                            Start Practice
                                        </x-button>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <!-- Upcoming Interviews -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Upcoming Interviews</h3>
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All →</a>
                            </div>

                            <div class="space-y-4">
                                @forelse($upcomingInterviews as $interview)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 hover:shadow-sm transition-all duration-200">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                                <!-- Company Avatar -->
                                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg flex items-center justify-center font-semibold text-sm">
                                                    {{ substr($interview['company'], 0, 1) }}
                                                </div>

                                                <!-- Interview Info -->
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-semibold text-gray-900 truncate mb-1">{{ $interview['role'] }}</h4>
                                                    <p class="text-sm text-gray-600 mb-2">{{ $interview['company'] }}</p>
                                                    <div class="flex items-center gap-3 flex-wrap">
                                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                                            <x-icon name="calendar" class="w-3 h-3" />
                                                            {{ $interview['date'] }}
                                                        </span>
                                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                                            <x-icon name="clock" class="w-3 h-3" />
                                                            {{ $interview['time'] }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                                            {{ $interview['type'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Button -->
                                            <div class="flex-shrink-0">
                                                <x-button outline primary sm icon="academic-cap">
                                                    Prepare
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                            <x-icon name="calendar" class="w-8 h-8 text-gray-400" />
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No upcoming interviews</h3>
                                        <p class="text-gray-600 mb-6">Schedule an interview or add an application to get started</p>
                                        <x-button primary icon="calendar">
                                            Schedule Interview
                                        </x-button>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Quick Actions & Setup -->
            <div class="space-y-6">
        <!-- Weekly Goals Progress -->
        @if($this->goalReminders)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Weekly Goals Progress</h3>
                    <span class="text-sm text-gray-500">Week of {{ now()->startOfWeek()->format('M j') }}</span>
                </div>

                <div class="space-y-4">
                    @foreach($this->goalReminders as $reminder)
                        <div class="flex items-center justify-between p-4 bg-{{ $reminder['color'] }}-50 border border-{{ $reminder['color'] }}-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-{{ $reminder['color'] }}-100 rounded-lg flex items-center justify-center">
                                    <x-icon name="{{ $reminder['icon'] }}" class="w-5 h-5 text-{{ $reminder['color'] }}-600" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $reminder['message'] }}</p>
                                </div>
                            </div>
                            <x-button
                                size="sm"
                                color="{{ $reminder['color'] }}"
                                wire:click="$dispatch('redirect', { url: '{{ $reminder['action_url'] }}' })"
                            >
                                {{ $reminder['action'] }}
                            </x-button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Actions</h3>
            <p class="text-sm text-gray-600 mb-4">Get started with your interview preparation</p>
                    <div class="space-y-2">
                        <x-button primary class="w-full justify-start" icon="play" wire:click="startPractice">
                            Start Practice
                        </x-button>
                        <x-button class="w-full justify-start" secondary icon="plus">
                            Add Interview
                        </x-button>
                        <x-button class="w-full justify-start" secondary icon="magnifying-glass">
                            Find Jobs
                        </x-button>
                        <x-button class="w-full justify-start" secondary icon="cog-6-tooth" wire:click="openGoalsModal">
                            Set Goals
                        </x-button>
                    </div>
                </div>

                <!-- Audio & Visual Setup -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Audio & Visual Setup</h3>
                    <div class="space-y-4">
                        @php
                            $setupMetrics = [
                                [
                                    'label' => 'Video Quality',
                                    'value' => $audioVisualSetup['video_quality'] ?? 75,
                                    'color' => 'bg-green-500',
                                ],
                                [
                                    'label' => 'Audio Quality',
                                    'value' => $audioVisualSetup['audio_quality'] ?? 75,
                                    'color' => 'bg-blue-500',
                                ],
                                [
                                    'label' => 'Background Quality',
                                    'value' => $audioVisualSetup['background_quality'] ?? 75,
                                    'color' => 'bg-blue-500',
                                ],
                                [
                                    'label' => 'Distracting Elements',
                                    'value' => $audioVisualSetup['distracting_elements'] ?? 75,
                                    'color' => 'bg-orange-500',
                                ],
                            ];
                        @endphp
                        @foreach($setupMetrics as $metric)
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-700 font-medium">{{ $metric['label'] }}</span>
                                    <span class="text-gray-900 font-semibold">{{ $metric['value'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="{{ $metric['color'] }} h-2 rounded-full transition-all duration-300" style="width: {{ $metric['value'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View Setup Tips →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personalized Recommendations -->
        <div class="mt-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Personalized Recommendations</h2>
                <p class="text-gray-600">AI-powered suggestions based on your recent performance</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recommendations as $recommendation)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <x-icon name="{{ $recommendation['icon'] }}" class="w-5 h-5 text-gray-600" />
                            </div>
                            @php
                                $priorityColors = [
                                    'high' => 'text-red-700 bg-red-50 border-red-200',
                                    'medium' => 'text-yellow-700 bg-yellow-50 border-yellow-200',
                                    'low' => 'text-green-700 bg-green-50 border-green-200',
                                ];
                                $priorityColor = $priorityColors[$recommendation['priority']] ?? 'text-gray-700 bg-gray-50 border-gray-200';
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded border text-xs font-medium {{ $priorityColor }}">
                                {{ ucfirst($recommendation['priority']) }}
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $recommendation['title'] }}</h4>
                        <p class="text-sm text-gray-600 mb-4">{{ $recommendation['description'] }}</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-500">{{ $recommendation['duration'] }} • {{ $recommendation['category'] }}</span>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Start →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Momentum Banner -->
        <div class="mt-8 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Keep up the momentum!</h3>
                        <p class="text-gray-700">You've improved 18% this month. Focus on these areas to reach interview-ready status for all companies.</p>
                    </div>
                </div>
                <x-button success lg icon="play">
                    Start Focused Practice
                </x-button>
            </div>
        </div>

        <!-- Recommended Jobs -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Recommended Jobs for You</h2>
                    <p class="text-gray-600">Based on your practice sessions and skill development</p>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium whitespace-nowrap">View All Jobs →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($recommendedJobs as $job)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $job['title'] }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-700">
                                        {{ $job['match'] }}% match
                                    </span>
                                </div>
                                <p class="text-gray-600">{{ $job['company'] }} • {{ $job['location'] }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $job['posted'] }} • {{ $job['applicants'] }} applicants</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3 border-y border-gray-200 mb-4">
                            <div>
                                <p class="text-lg font-semibold text-gray-900">{{ $job['salary'] }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-gray-600">Practice Score:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $job['practice_score'] }}/10</span>
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            @php
                                $readinessColors = [
                                    'High' => 'bg-green-100 text-green-700',
                                    'Medium' => 'bg-yellow-100 text-yellow-700',
                                    'Low' => 'bg-red-100 text-red-700',
                                ];
                                $readinessColor = $readinessColors[$job['readiness']] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium {{ $readinessColor }}">
                                {{ $job['readiness'] }}
                            </span>
                        </div>

                        <div class="mb-4 space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-gray-600 font-medium">Strong Skills:</span>
                                @foreach($job['strong_skills'] as $skill)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-gray-600 font-medium">Practice Areas:</span>
                                @foreach($job['practice_areas'] as $area)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <x-button class="w-full" primary>
                                Apply Now
                            </x-button>
                            <x-button class="w-full" secondary icon="play">
                                Practice
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Product Role Insights -->
        <div class="mt-8 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200 p-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-icon name="light-bulb" class="w-6 h-6 text-purple-600" solid />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Product Role Insights</h3>
                        <p class="text-gray-700">{{ $productInsights['message'] }}</p>
                    </div>
                </div>
                <x-button primary lg class="bg-purple-600 hover:bg-purple-700 border-purple-600">
                    {{ $productInsights['action'] }}
                </x-button>
            </div>
        </div>
    </div>

    <!-- Interview Interface Modal -->
    @if($showInterviewInterface)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <livewire:interview-interface
                        :job-posting-id="$selectedJobPosting?->id"
                        wire:key="interview-interface-{{ $selectedJobPosting?->id }}"
                    />
                </div>
            </div>
        </div>
    @endif

    <!-- Goals Modal Component -->
    @livewire('goals-modal')

</div>
