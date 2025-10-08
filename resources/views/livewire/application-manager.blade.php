<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Job Applications</h1>
                <p class="mt-2 text-lg text-gray-600">Track your applications, prepare for interviews, and discover new opportunities.</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Applications -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <p class="text-sm font-medium text-gray-600">Total Applications</p>
                            <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-2">5</p>
                        <p class="text-sm text-gray-500 mt-1">Across all stages</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Interviews -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <p class="text-sm font-medium text-gray-600">Upcoming Interviews</p>
                            <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-2">3</p>
                        <p class="text-sm text-purple-600 mt-1">This week</p>
                    </div>
                </div>
            </div>

            <!-- Interview Ready -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <p class="text-sm font-medium text-gray-600">Interview Ready</p>
                            <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-2">82%</p>
                        <p class="text-sm text-green-600 mt-1">Great progress!</p>
                    </div>
                </div>
            </div>

            <!-- Offers Received -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center">
                            <p class="text-sm font-medium text-gray-600">Offers Received</p>
                            <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-2">1</p>
                        <p class="text-sm text-gray-500 mt-1">Pending decision</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button
                        wire:click="setActiveTab('my_applications')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'my_applications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        My Applications
                        <span class="ml-2 bg-gray-900 text-white py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $totalApplications }}</span>
                    </button>
                    <button
                        wire:click="setActiveTab('upcoming_interviews')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'upcoming_interviews' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Upcoming Interviews
                        <span class="ml-2 bg-orange-500 text-white py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $upcomingInterviewsCount }}</span>
                    </button>
                    <button
                        wire:click="setActiveTab('recommended_jobs')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'recommended_jobs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Recommended Jobs
                        <span class="ml-2 bg-green-500 text-white py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $recommendedJobs->count() }}</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Applications Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-semibold text-gray-900">Job Applications</h2>

                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input
                            wire:model.live="search"
                            type="text"
                            placeholder="Search applications..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                    </div>

                    <!-- Sort -->
                    <div class="relative">
                        <button
                            wire:click="sortBy('application_date')"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            Applied Date
                            <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Add Application -->
                    <x-button wire:click="openCreateModal" class="bg-purple-600 hover:bg-purple-700 text-white" icon="plus">
                        Add Application
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'my_applications')
            <!-- Applications List -->
            <div class="space-y-4">
                @forelse($applications as $application)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Job Title and Company -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $application['position_title'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $application['company_name'] }}</p>
                                </div>

                                <!-- Status Badge -->
                                <div class="ml-4">
                                    @php
                                        $statusColors = [
                                            'applied' => 'bg-gray-100 text-gray-800',
                                            'screening' => 'bg-blue-100 text-blue-800',
                                            'interview' => 'bg-purple-100 text-purple-800',
                                            'offer' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'withdrawn' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $statusColor = $statusColors[$application['status']] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst($application['status']) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Location and Salary -->
                            @if($application['location'] || $application['salary_min'] || $application['salary_max'])
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    @if($application['location'])
                                        <div class="flex items-center mr-6">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $application['location'] }}
                                        </div>
                                    @endif
                                    @if($application['salary_min'] || $application['salary_max'])
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            @if($application['salary_min'] && $application['salary_max'])
                                                ${{ number_format($application['salary_min']) }}k - ${{ number_format($application['salary_max']) }}k
                                            @elseif($application['salary_min'])
                                                ${{ number_format($application['salary_min']) }}k+
                                            @elseif($application['salary_max'])
                                                Up to ${{ number_format($application['salary_max']) }}k
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Dates -->
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <div class="flex items-center mr-6">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Applied {{ \Carbon\Carbon::parse($application['application_date'])->format('M j, Y') }}
                                </div>
                                @if($application['expected_response_date'])
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Interview {{ \Carbon\Carbon::parse($application['expected_response_date'])->format('M j, Y') }}
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-3">
                                <x-button
                                    wire:click="openEditModal({{ $application['id'] }})"
                                    class="text-gray-600 hover:text-gray-900 bg-transparent border border-gray-300 hover:bg-gray-50"
                                    size="sm"
                                    icon="document-text"
                                >
                                    Notes
                                </x-button>
                                <x-button
                                    wire:click="updateStatus({{ $application['id'] }}, 'interview')"
                                    class="bg-purple-600 hover:bg-purple-700 text-white"
                                    size="sm"
                                    icon="play"
                                >
                                    Practice
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No applications</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first job application.</p>
                    <div class="mt-6">
                        <x-button wire:click="openCreateModal" primary icon="plus">
                            Add Application
                        </x-button>
                    </div>
                </div>
                @endforelse
            </div>

        @elseif($activeTab === 'upcoming_interviews')
            <!-- Upcoming Interviews Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Upcoming Interviews</h2>
                    <x-button wire:click="redirectToUpcomingInterviews" class="bg-blue-600 hover:bg-blue-700 text-white" icon="plus">
                        Add Interview
                    </x-button>
                </div>
            </div>

            <!-- Interviews List -->
            <div class="space-y-4">
                @forelse($upcomingInterviews as $interview)
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <!-- Left side - Avatar and Details -->
                            <div class="flex items-center space-x-4">
                                <!-- Company Avatar -->
                                <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">
                                        {{ strtoupper(substr($interview['company'], 0, 1)) }}
                                    </span>
                                </div>

                                <!-- Interview Details -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $interview['position'] }}</h3>
                                        @if($interview['readiness_score'] >= 80)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Ready</span>
                                        @elseif($interview['readiness_score'] >= 60)
                                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Almost Ready</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Needs Work</span>
                                        @endif
                                    </div>

                                    <p class="text-gray-600 mb-3">{{ $interview['company'] }}</p>

                                    <!-- Schedule Details -->
                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($interview['interview_date'])->format('M j, Y') }}</span>
                                        </div>

                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($interview['interview_time'])->format('g:i A') }}</span>
                                        </div>

                                        @if($interview['interview_type'] === 'mixed')
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>mixed</span>
                                            </div>
                                        @elseif($interview['interview_type'] === 'technical')
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span>technical</span>
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <span>behavioral</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right side - Readiness and Actions -->
                            <div class="flex items-center space-x-4">
                                <!-- Readiness Score -->
                                <div class="text-right">
                                    <div class="text-2xl font-bold {{ $interview['readiness_score'] >= 80 ? 'text-green-600' : ($interview['readiness_score'] >= 60 ? 'text-orange-500' : 'text-red-500') }}">
                                        {{ $interview['readiness_score'] }}%
                                    </div>
                                    <div class="text-sm text-gray-500">Ready</div>
                                </div>

                                <!-- Practice Button -->
                                <x-button
                                    wire:click="startPractice({{ $interview['id'] }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white"
                                    icon="play"
                                >
                                    Practice
                                </x-button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming interviews</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by scheduling your first interview.</p>
                        <div class="mt-6">
                            <x-button wire:click="redirectToUpcomingInterviews" class="bg-blue-600 hover:bg-blue-700 text-white" icon="plus">
                                Add Interview
                            </x-button>
                        </div>
                    </div>
                @endforelse
            </div>

        @elseif($activeTab === 'recommended_jobs')
            <!-- Recommended Jobs Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Recommended Jobs for You</h2>
                        <p class="mt-1 text-sm text-gray-600">Based on your practice sessions and skill development</p>
                    </div>
                    <x-button wire:click="viewAllJobs" class="bg-blue-600 hover:bg-blue-700 text-white" icon="eye">
                        View All Jobs
                    </x-button>
                </div>
            </div>

            <!-- Recommended Jobs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @forelse($recommendedJobs as $job)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <!-- Job Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $job['position_title'] }}</h3>
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ $job['match_percentage'] }}% match
                                    </span>
                                </div>

                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                    <span class="font-medium">{{ $job['company_name'] }}</span>
                                    <span>•</span>
                                    <span>{{ $job['location'] }}</span>
                                </div>

                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>Posted {{ $job['posted_days_ago'] }} {{ $job['posted_days_ago'] == 1 ? 'day' : 'days' }} ago</span>
                                    <span>•</span>
                                    <span>{{ $job['applicants_count'] }} applicants</span>
                                    <span>•</span>
                                    <span>${{ number_format($job['salary_min']) }}k - ${{ number_format($job['salary_max']) }}k</span>
                                </div>
                            </div>
                        </div>

                        <!-- Practice Score -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Your Practice Score</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $job['practice_score'] }}/10</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($job['practice_score'] / 10) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Interview Readiness -->
                        <div class="mb-4">
                            <span class="text-sm font-medium text-gray-700">Interview Readiness: </span>
                            @if($job['interview_readiness'] === 'High')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">High</span>
                            @elseif($job['interview_readiness'] === 'Medium')
                                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Medium</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Low</span>
                            @endif
                        </div>

                        <!-- Skills -->
                        <div class="mb-4">
                            <div class="mb-2">
                                <span class="text-sm font-medium text-gray-700">Strong Skills:</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($job['strong_skills'] as $skill)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-700">Practice Areas:</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($job['practice_areas'] as $area)
                                        <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">{{ $area }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <x-button
                                wire:click="applyToJob({{ $job['id'] }})"
                                class="bg-blue-600 hover:bg-blue-700 text-white flex-1"
                                icon="plus"
                            >
                                Apply Now
                            </x-button>
                            <x-button
                                wire:click="practiceForJob({{ $job['id'] }})"
                                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50"
                                icon="play"
                            >
                                Practice
                            </x-button>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No job recommendations</h3>
                        <p class="mt-1 text-sm text-gray-500">We'll analyze your profile and suggest relevant opportunities.</p>
                        <div class="mt-6">
                            <x-button wire:click="refreshRecommendations" class="bg-blue-600 hover:bg-blue-700 text-white" icon="refresh">
                                Refresh Recommendations
                            </x-button>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Product Role Insights -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Product Role Insights</h3>
                        <p class="text-gray-600 mb-4">
                            Your product strategy and analytical skills are strong based on practice sessions. Focus on stakeholder management and executive communication to unlock senior product leadership roles.
                        </p>
                        <x-button wire:click="getProductJobAlerts" class="bg-blue-600 hover:bg-blue-700 text-white" icon="bell">
                            Get Product Job Alerts
                        </x-button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Add New Application</h3>
                    <button
                        wire:click="closeCreateModal"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit="createApplication" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input
                                wire:model="company_name"
                                type="text"
                                id="company_name"
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                            @error('company_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="position_title" class="block text-sm font-medium text-gray-700 mb-2">Position Title</label>
                            <input
                                wire:model="position_title"
                                type="text"
                                id="position_title"
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                            @error('position_title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="job_url" class="block text-sm font-medium text-gray-700 mb-2">Job URL (Optional)</label>
                        <input
                            wire:model="job_url"
                            type="url"
                            id="job_url"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('job_url')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select
                                wire:model="status"
                                id="status"
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            >
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select
                                wire:model="priority"
                                id="priority"
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            >
                                @foreach($priorityOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('priority')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="application_date" class="block text-sm font-medium text-gray-700 mb-2">Application Date</label>
                            <input
                                wire:model="application_date"
                                type="date"
                                id="application_date"
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                            @error('application_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-2">Salary Min (Optional)</label>
                            <input
                                wire:model="salary_min"
                                type="number"
                                id="salary_min"
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            >
                            @error('salary_min')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="salary_max" class="block text-sm font-medium text-gray-700 mb-2">Salary Max (Optional)</label>
                            <input
                                wire:model="salary_max"
                                type="number"
                                id="salary_max"
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            >
                            @error('salary_max')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location (Optional)</label>
                        <input
                            wire:model="location"
                            type="text"
                            id="location"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('location')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea
                            wire:model="notes"
                            id="notes"
                            rows="3"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                        @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <x-button wire:click="closeCreateModal" secondary>
                            Cancel
                        </x-button>
                        <x-button type="submit" primary>
                            Add Application
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
