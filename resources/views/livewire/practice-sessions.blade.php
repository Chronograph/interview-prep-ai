<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50">
    <!-- Hero Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Practice Sessions
                        </h1>
                    </div>
                    <p class="text-gray-600 text-lg">Master your interview skills with AI-powered practice sessions</p>
                </div>
                <div class="flex gap-3">
                    <x-button wire:click="startPractice" primary lg icon="video-camera" class="shadow-lg hover:shadow-xl transition-shadow">
                        Start Practice
                    </x-button>
                    <x-button :href="route('practice.sessions')" outline lg icon="clock" class="shadow-sm hover:shadow-md transition-shadow">
                        View History
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards with Modern Design -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Sessions Card -->
            <x-card class="border-l-4 border-l-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Sessions</h3>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalSessions }}</p>
                        <div class="flex items-center gap-1 mt-2">
                            <x-icon name="arrow-trending-up" class="w-4 h-4 text-green-500" />
                            <span class="text-sm font-medium text-green-600">+{{ $sessionsThisWeek }} this week</span>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <x-icon name="video-camera" class="w-8 h-8 text-blue-600" />
                    </div>
                </div>
            </x-card>

            <!-- Average Score Card -->
            <x-card class="border-l-4 border-l-green-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Average Score</h3>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $averageScore }}<span class="text-2xl text-gray-500">/10</span></p>
                        <div class="flex items-center gap-1 mt-2">
                            <x-icon name="arrow-trending-up" class="w-4 h-4 text-green-500" />
                            <span class="text-sm font-medium text-green-600">+{{ $scoreImprovement }}% improvement</span>
                        </div>
                    </div>
                    <div class="p-3 bg-green-100 rounded-xl">
                        <x-icon name="check-circle" class="w-8 h-8 text-green-600" />
                    </div>
                </div>
            </x-card>

            <!-- Interview Ready Card -->
            <x-card class="border-l-4 border-l-purple-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Interview Ready</h3>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $interviewReadyCount }}</p>
                        <p class="text-sm text-gray-500 mt-2">Companies ready</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-xl">
                        <x-icon name="building-office" class="w-8 h-8 text-purple-600" />
                    </div>
                </div>
            </x-card>

            <!-- Need Practice Card -->
            <x-card class="border-l-4 border-l-orange-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Need Practice</h3>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $needPracticeCount }}</p>
                        <p class="text-sm text-orange-600 mt-2">Skills requiring focus</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-xl">
                        <x-icon name="exclamation-triangle" class="w-8 h-8 text-orange-600" />
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Practice Session Types -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Choose Your Practice Mode</h2>
            <p class="text-gray-600">Select a practice type that matches your interview preparation needs</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Behavioral Interview -->
            <x-card wire:click="openSessionTypeModal('behavioral')" class="hover:shadow-xl transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-100 rounded-xl group-hover:bg-blue-200 transition-colors">
                        <x-icon name="user-group" class="w-8 h-8 text-blue-600" />
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Popular
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Behavioral Interview</h3>
                <p class="text-gray-600 mb-4">Practice answering behavioral questions using the STAR method</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Leadership</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Teamwork</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Problem Solving</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>⏱️ 30-45 min</span>
                    <span class="text-blue-600 font-medium group-hover:text-blue-700">Start →</span>
                </div>
            </x-card>

            <!-- Technical Interview -->
            <x-card wire:click="openSessionTypeModal('technical')" class="hover:shadow-xl transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-100 rounded-xl group-hover:bg-purple-200 transition-colors">
                        <x-icon name="code-bracket" class="w-8 h-8 text-purple-600" />
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Advanced
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Technical Interview</h3>
                <p class="text-gray-600 mb-4">Coding challenges, algorithms, and system design questions</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Algorithms</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Data Structures</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">System Design</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>⏱️ 45-60 min</span>
                    <span class="text-purple-600 font-medium group-hover:text-purple-700">Start →</span>
                </div>
            </x-card>

            <!-- Product Interview -->
            <x-card wire:click="openSessionTypeModal('product')" class="hover:shadow-xl transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-100 rounded-xl group-hover:bg-green-200 transition-colors">
                        <x-icon name="light-bulb" class="w-8 h-8 text-green-600" />
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Product Interview</h3>
                <p class="text-gray-600 mb-4">Product sense, design, and strategy questions for PM roles</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Product Sense</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Strategy</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Metrics</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>⏱️ 30-45 min</span>
                    <span class="text-green-600 font-medium group-hover:text-green-700">Start →</span>
                </div>
            </x-card>

            <!-- Case Study -->
            <x-card wire:click="openSessionTypeModal('case-study')" class="hover:shadow-xl transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-orange-100 rounded-xl group-hover:bg-orange-200 transition-colors">
                        <x-icon name="clipboard-document-list" class="w-8 h-8 text-orange-600" />
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Case Study</h3>
                <p class="text-gray-600 mb-4">Business cases and consulting-style problem solving</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Analysis</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Problem Solving</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Business</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>⏱️ 45-60 min</span>
                    <span class="text-orange-600 font-medium group-hover:text-orange-700">Start →</span>
                </div>
            </x-card>

            <!-- Company-Specific -->
            <x-card wire:click="openSessionTypeModal('company-specific')" class="hover:shadow-xl transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-pink-100 rounded-xl group-hover:bg-pink-200 transition-colors">
                        <x-icon name="building-office-2" class="w-8 h-8 text-pink-600" />
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                        Targeted
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Company-Specific</h3>
                <p class="text-gray-600 mb-4">Practice for specific companies and their unique styles</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">FAANG</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Startups</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Enterprise</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>⏱️ 30-60 min</span>
                    <span class="text-pink-600 font-medium group-hover:text-pink-700">Start →</span>
                </div>
            </x-card>

            <!-- Quick Practice -->
            <x-card wire:click="openSessionTypeModal('quick')" class="hover:shadow-xl transition-all duration-300 cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-100 rounded-xl group-hover:bg-indigo-200 transition-colors">
                        <x-icon name="bolt" class="w-8 h-8 text-indigo-600" />
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Quick
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Quick Practice</h3>
                <p class="text-gray-600 mb-4">5 rapid-fire questions to sharpen your skills</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Mixed</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Fast</span>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">Daily</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>⏱️ 10-15 min</span>
                    <span class="text-indigo-600 font-medium group-hover:text-indigo-700">Start →</span>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Recent Practice Sessions -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Recent Practice Sessions</h2>
                    <p class="text-gray-600">Review your progress and continue where you left off</p>
                </div>
                <x-button :href="route('practice.sessions')" sm outline icon="arrow-right">
                    View All
                </x-button>
            </div>

            <div class="space-y-4">
                @forelse($practiceSessions as $session)
                    <x-card class="hover:shadow-lg transition-shadow border border-gray-200">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 flex-1">
                                <!-- Session Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-lg font-bold text-white">{{ $session['company_initial'] }}</span>
                                    </div>
                                </div>

                                <!-- Session Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <h3 class="font-semibold text-gray-900">{{ $session['role'] }}</h3>
                                        @php
                                            $difficultyColor = match($session['difficulty']) {
                                                'Hard' => 'bg-red-100 text-red-800',
                                                'Medium' => 'bg-yellow-100 text-yellow-800',
                                                'Easy' => 'bg-green-100 text-green-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $difficultyColor }}">
                                            {{ $session['difficulty'] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $session['company'] }} • {{ $session['date'] }}</p>
                                    @if($session['score'])
                                        @php
                                            $scoreColor = $session['score'] >= 7 ? 'bg-green-100 text-green-800' : ($session['score'] >= 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                        @endphp
                                        <div class="flex items-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium {{ $scoreColor }}">
                                                <x-icon name="star" class="w-3 h-3" solid />
                                                {{ $session['score'] }}/10
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Button -->
                            <x-button sm primary icon="eye">
                                View
                            </x-button>
                        </div>
                    </x-card>
                @empty
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <x-icon name="document-text" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No practice sessions yet</h3>
                        <p class="text-gray-600 mb-4">Start your first practice session to begin improving your interview skills</p>
                        <x-button wire:click="startPractice" primary icon="video-camera">
                            Start Practice Session
                        </x-button>
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>

    <!-- Start Practice Modal -->
    @if($showStartPracticeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div wire:click="closeModals" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    
                    <!-- Modal Header -->
                    <div class="px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Start Practice Session</h3>
                                <p class="text-gray-600 mt-1">Choose the type of interview practice that best fits your current goals and preparation needs.</p>
                            </div>
                            <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-8 py-6">
                        <!-- Practice Session Options Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            <!-- Card 1: Practice Generic Role-Specific Interviews -->
                            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer" wire:click="openSessionTypeModal('role-specific')">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Practice Generic Role-Specific Interviews</h4>
                                        <p class="text-gray-600 text-sm mb-4">Practice common interview questions for Product Manager and Product Design Manager roles</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Intermediate</span>
                                    <button class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                                        <span class="text-sm font-medium">Start Session</span>
                                        <div class="w-6 h-6 bg-gray-700 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Card 2: Refine Elevator Pitch -->
                            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer" wire:click="openSessionTypeModal('elevator-pitch')">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Refine Elevator Pitch</h4>
                                        <p class="text-gray-600 text-sm mb-4">Perfect your 30-60 second personal introduction and value proposition</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Beginner</span>
                                    <button class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                                        <span class="text-sm font-medium">Start Session</span>
                                        <div class="w-6 h-6 bg-gray-700 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Card 3: Add a New Job Interview -->
                            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer" wire:click="$dispatch('open-add-interview-modal')">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Add a New Job Interview</h4>
                                        <p class="text-gray-600 text-sm mb-4">Schedule practice for a specific company and role you're interviewing for</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Advanced</span>
                                    <button class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                                        <span class="text-sm font-medium">Start Session</span>
                                        <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Card 4: Level Up Skills -->
                            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer" wire:click="openSessionTypeModal('skill-improvement')">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Level Up Skills</h4>
                                        <p class="text-gray-600 text-sm mb-4">Focus on specific competencies based on your performance analytics</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Intermediate</span>
                                    <button class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                                        <span class="text-sm font-medium">Start Session</span>
                                        <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16,6L18.29,8.29L13.41,13.17L9.41,9.17L2,16.59L3.41,18L9.41,12L13.41,16L19.71,9.71L22,12V6H16Z"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Practice Stats Section -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-1">Your Practice Stats</h4>
                                    <p class="text-gray-600 text-sm">{{ $totalSessions }} sessions completed • {{ $averageScore }} avg score • {{ $scoreImprovement }}% improvement this month</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-gray-900">{{ $averageScore }}/10</div>
                                    <div class="text-green-600 font-medium">Ready for interviews</div>
                                </div>
                            </div>
                        </div>

                        <!-- Pro Tip Section -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11M11,9H13V7H11V9Z"/>
                                    </svg>
                                </div>
                                <p class="text-blue-800 text-sm">
                                    <strong>Pro tip:</strong> Start with generic role-specific interviews if you're new, or jump into company-specific practice if you have an upcoming interview.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Session Configuration Modal -->
    @if($showSessionTypeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div wire:click="closeModals" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-gradient-to-br from-blue-600 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-bold text-white capitalize">{{ str_replace('-', ' ', $selectedSessionType) }} Practice</h3>
                            <button wire:click="closeModals" class="text-white hover:text-gray-200">
                                <x-icon name="x-mark" class="w-6 h-6" />
                            </button>
                        </div>
                    </div>
                    <div class="bg-white px-6 py-6 space-y-6">
                        <!-- Difficulty Level -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Difficulty Level</label>
                            <div class="grid grid-cols-3 gap-3">
                                <x-button wire:click="$set('selectedDifficulty', 'easy')" :primary="$selectedDifficulty === 'easy'" :outline="$selectedDifficulty !== 'easy'" class="w-full">
                                    Easy
                                </x-button>
                                <x-button wire:click="$set('selectedDifficulty', 'medium')" :primary="$selectedDifficulty === 'medium'" :outline="$selectedDifficulty !== 'medium'" class="w-full">
                                    Medium
                                </x-button>
                                <x-button wire:click="$set('selectedDifficulty', 'hard')" :primary="$selectedDifficulty === 'hard'" :outline="$selectedDifficulty !== 'hard'" class="w-full">
                                    Hard
                                </x-button>
                            </div>
                        </div>

                        <!-- Focus Area -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Focus Area</label>
                            <x-select wire:model="selectedFocusArea" placeholder="Select focus area">
                                <x-select.option label="General Practice" value="general" />
                                <x-select.option label="Leadership" value="leadership" />
                                <x-select.option label="Problem Solving" value="problem-solving" />
                                <x-select.option label="Communication" value="communication" />
                                <x-select.option label="Technical Skills" value="technical-skills" />
                                <x-select.option label="Product Sense" value="product-sense" />
                            </x-select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4">
                            <x-button wire:click="closeModals" outline class="flex-1">
                                Cancel
                            </x-button>
                            <x-button wire:click="startSession" primary icon="play" class="flex-1">
                                Start Session
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Include the Add New Interview Modal -->
    @livewire('add-new-interview-modal')
</div>
