<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Practice Sessions</h1>
            <p class="text-gray-600">Improve your interview skills with targeted practice sessions.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sessions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSessions }}</p>
                        <p class="text-sm text-green-600">+{{ $sessionsThisWeek }} this week</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Avg. Score</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $averageScore }}/10</p>
                        <p class="text-sm text-green-600">+{{ $scoreImprovement }}% improvement</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Interview Ready -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Interview Ready</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $interviewReadyCount }}</p>
                        <p class="text-sm text-gray-500">Companies</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Need Practice -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Need Practice</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $needPracticeCount }}</p>
                        <p class="text-sm text-orange-600">Companies requiring focus</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button
                        wire:click="setActiveTab('upcoming_interviews')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'upcoming_interviews' ? 'border-blue-500 text-blue-600 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Upcoming Interviews
                    </button>
                    <button
                        wire:click="setActiveTab('by_role')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'by_role' ? 'border-blue-500 text-blue-600 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        By Role
                    </button>
                    <button
                        wire:click="setActiveTab('the_basics')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'the_basics' ? 'border-blue-500 text-blue-600 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        The Basics
                    </button>
                    <button
                        wire:click="setActiveTab('elevator_pitch')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'elevator_pitch' ? 'border-blue-500 text-blue-600 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Elevator Pitch
                    </button>
                    <button
                        wire:click="setActiveTab('skills')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'skills' ? 'border-blue-500 text-blue-600 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Skills
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'upcoming_interviews')
            <!-- Practice for Upcoming Interviews -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Practice for Upcoming Interviews</h2>
                    <p class="text-gray-600">Prepare for your scheduled interviews with company-specific practice sessions.</p>
                </div>

                <div class="space-y-4">
                    @forelse($upcomingInterviews as $interview)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <!-- Left side - Interview Details -->
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Company Avatar -->
                                    <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ $interview['company_initial'] }}</span>
                                    </div>

                                    <!-- Interview Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $interview['company'] }}</h3>
                                            <span class="text-gray-600">{{ $interview['role'] }}</span>
                                        </div>

                                        <!-- Date and Focus Areas -->
                                        <div class="flex items-center space-x-6 text-sm text-gray-600 mb-3">
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ \Carbon\Carbon::parse($interview['date'])->format('M j, Y') }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                                </svg>
                                                <span>{{ $interview['focus_areas'] }}</span>
                                            </div>
                                        </div>

                                        <!-- Readiness Score -->
                                        <div class="mb-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-700">Readiness Score</span>
                                                <span class="text-sm font-semibold {{ $interview['readiness_status']['color'] }}">
                                                    {{ $interview['readiness_score'] }}% - {{ $interview['readiness_status']['text'] }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $interview['readiness_score'] }}%"></div>
                                            </div>
                                        </div>

                                        <!-- Resource Buttons -->
                                        <div class="flex gap-3">
                                            <x-button
                                                wire:click="viewCompanySheet({{ $interview['id'] }})"
                                                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50"
                                                size="sm"
                                                icon="document-text"
                                            >
                                                Company Sheet
                                            </x-button>
                                            <x-button
                                                wire:click="viewRoleGuide({{ $interview['id'] }})"
                                                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50"
                                                size="sm"
                                                icon="building-office"
                                            >
                                                Role Guide
                                            </x-button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right side - Status and Action -->
                                <div class="flex flex-col items-end space-y-3">
                                    <!-- Practice Status -->
                                    <span class="text-sm font-medium {{ $interview['practice_status']['color'] }}">
                                        {{ $interview['practice_status']['text'] }}
                                    </span>

                                    <!-- Start Practice Button -->
                                    <x-button
                                        wire:click="startPractice({{ $interview['id'] }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white"
                                        icon="play"
                                    >
                                        Start Practice
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming interviews</h3>
                            <p class="mt-1 text-sm text-gray-500">Schedule interviews to see practice recommendations here.</p>
                            <div class="mt-6">
                                <x-button wire:click="startPractice()" class="bg-blue-600 hover:bg-blue-700 text-white" icon="plus">
                                    Start General Practice
                                </x-button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        @elseif($activeTab === 'by_role')
            <!-- By Role Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Practice by Role</h2>
                <p class="text-gray-600">Coming soon - role-specific practice sessions.</p>
            </div>

        @elseif($activeTab === 'the_basics')
            <!-- The Basics Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">The Basics</h2>
                <p class="text-gray-600">Coming soon - fundamental interview skills practice.</p>
            </div>

        @elseif($activeTab === 'elevator_pitch')
            <!-- Elevator Pitch Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Elevator Pitch</h2>
                <p class="text-gray-600">Coming soon - elevator pitch practice sessions.</p>
            </div>

        @elseif($activeTab === 'skills')
            <!-- Skills Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Skills Assessment</h2>
                <p class="text-gray-600">Coming soon - skills-based practice sessions.</p>
            </div>
        @endif
    </div>
</div>
