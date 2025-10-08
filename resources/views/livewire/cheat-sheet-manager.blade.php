<div class="max-w-6xl mx-auto p-6 space-y-8">
    <!-- Header -->
    <div class="text-center space-y-2">
        <h1 class="text-4xl font-bold text-gray-900">Interview Cheat Sheets</h1>
        <p class="text-gray-600">Personalized preparation guides for each interview with company insights, talking points, and practice responses.</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg w-fit mx-auto">
        <button
            wire:click="setActiveTab('company_interviews')"
            class="flex items-center gap-2 px-4 py-2 rounded-md font-medium transition-all duration-200"
            @class([
                'bg-white text-gray-900 shadow-sm' => $activeTab === 'company_interviews',
                'text-gray-600 hover:text-gray-900' => $activeTab !== 'company_interviews'
            ])
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Company Interviews
        </button>
        <button
            wire:click="setActiveTab('role_guides')"
            class="flex items-center gap-2 px-4 py-2 rounded-md font-medium transition-all duration-200"
            @class([
                'bg-white text-gray-900 shadow-sm' => $activeTab === 'role_guides',
                'text-gray-600 hover:text-gray-900' => $activeTab !== 'role_guides'
            ])
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Role Guides
        </button>
    </div>

    <!-- Search and Action Bar -->
    <div class="flex items-center justify-between">
        <!-- Search Bar -->
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                wire:model.live="search"
                type="text"
                placeholder="{{ $activeTab === 'company_interviews' ? 'Search company interviews...' : 'Search role guides...' }}"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
            >
        </div>

        <!-- Generate Button -->
        <x-button wire:click="openCreateModal" primary class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Generate Cheat Sheet
        </x-button>
    </div>

    <!-- Cheat Sheets Grid -->
    @if($cheatSheets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cheatSheets as $cheatSheet)
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <!-- Header with Icon and Title -->
                    <div class="flex items-start gap-3 mb-4">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            @if($cheatSheet->jobPosting)
                                <h3 class="font-semibold text-gray-900">{{ $cheatSheet->jobPosting->company }}</h3>
                                <p class="text-sm text-gray-600">{{ $cheatSheet->jobPosting->title }}</p>
                            @else
                                <h3 class="font-semibold text-gray-900">{{ $cheatSheet->title }}</h3>
                                <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $cheatSheet->category)) }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2 mb-4">
                        @if($cheatSheet->interview_date)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Interview Date</span>
                                <span class="text-gray-900">{{ $cheatSheet->interview_date->format('n/j/Y') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Created</span>
                            <span class="text-gray-900">{{ $cheatSheet->created_at->format('n/j/Y') }}</span>
                        </div>
                    </div>

                    <!-- Metrics -->
                    <div class="flex gap-4 mb-6">
                        @if($cheatSheet->jobPosting)
                            <span class="text-blue-600 text-sm font-medium">{{ count($cheatSheet->jobPosting->skills ?? []) }} Roles</span>
                            <span class="text-blue-600 text-sm font-medium">{{ count($cheatSheet->key_points ?? []) }} Skills</span>
                            <span class="text-blue-600 text-sm font-medium">{{ count($cheatSheet->follow_up_questions ?? []) }} Topics</span>
                        @else
                            <span class="text-blue-600 text-sm font-medium">{{ count($cheatSheet->key_points ?? []) }} Roles</span>
                            <span class="text-blue-600 text-sm font-medium">{{ count($cheatSheet->examples ?? []) }} Skills</span>
                            <span class="text-blue-600 text-sm font-medium">{{ count($cheatSheet->follow_up_questions ?? []) }} Topics</span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <x-button
                            wire:click="viewCheatSheet({{ $cheatSheet->id }})"
                            size="sm"
                            class="flex-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </x-button>
                        <x-button
                            wire:click="downloadCheatSheet({{ $cheatSheet->id }})"
                            size="sm"
                            class="flex-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($cheatSheets->hasPages())
            <div class="flex justify-center">
                {{ $cheatSheets->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="p-4 bg-gray-100 rounded-lg w-16 h-16 mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400 mx-auto mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No cheat sheets found</h3>
            <p class="text-gray-600 mb-6">Get started by generating your first cheat sheet.</p>
            <x-button wire:click="openCreateModal" primary class="inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Generate Cheat Sheet
            </x-button>
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $showCreateModal ? 'Generate New Cheat Sheet' : 'Edit Cheat Sheet' }}
                    </h3>
                </div>

                <form wire:submit="save" class="p-6 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input
                            wire:model="title"
                            type="text"
                            id="title"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g., Tell Me About Yourself"
                        >
                        @error('title')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="job_posting_id" class="block text-sm font-medium text-gray-700 mb-2">Job Posting (Optional)</label>
                        <select
                            wire:model="job_posting_id"
                            id="job_posting_id"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Select a job posting...</option>
                            @foreach($jobPostings as $jobPosting)
                                <option value="{{ $jobPosting->id }}">{{ $jobPosting->title }} at {{ $jobPosting->company }}</option>
                            @endforeach
                        </select>
                        @error('job_posting_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="interview_date" class="block text-sm font-medium text-gray-700 mb-2">Interview Date (Optional)</label>
                        <input
                            wire:model="interview_date"
                            type="date"
                            id="interview_date"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('interview_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea
                            wire:model="notes"
                            id="notes"
                            rows="4"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Any specific requirements or context for this cheat sheet..."
                        ></textarea>
                        @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-button type="button" wire:click="closeModals" secondary>
                            Cancel
                        </x-button>
                        <x-button type="submit" primary>
                            {{ $showCreateModal ? 'Generate Cheat Sheet' : 'Update Cheat Sheet' }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- View Modal -->
    @if($showViewModal && $viewingCheatSheet)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">
                                @if($viewingCheatSheet->jobPosting)
                                    {{ $viewingCheatSheet->jobPosting->company }} - {{ $viewingCheatSheet->jobPosting->title }}
                                @else
                                    {{ $viewingCheatSheet->title }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Interview preparation guide</p>
                            @if($viewingCheatSheet->interview_date)
                                <p class="text-sm text-gray-500 mt-1">Interview Date: {{ $viewingCheatSheet->interview_date->format('n/j/Y') }}</p>
                            @endif
                        </div>
                        <button
                            wire:click="closeModals"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <nav class="flex space-x-8">
                        <button
                            wire:click="$set('activeViewTab', 'company')"
                            class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeViewTab === 'company' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            Company Info
                        </button>
                        <button
                            wire:click="$set('activeViewTab', 'talking')"
                            class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeViewTab === 'talking' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            Talking Points
                        </button>
                        <button
                            wire:click="$set('activeViewTab', 'topics')"
                            class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeViewTab === 'topics' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            Interview Topics
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    @if($activeViewTab === 'company')
                        <!-- Company Info Tab -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">Company Overview</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    @if($viewingCheatSheet->jobPosting)
                                        {{ $viewingCheatSheet->jobPosting->company }} is a leading technology company specializing in innovative solutions and digital transformation. The company focuses on delivering exceptional products and services while maintaining a strong commitment to innovation and customer satisfaction.
                                    @else
                                        {{ $viewingCheatSheet->topic_description ?? 'Comprehensive preparation guide for interview success.' }}
                                    @endif
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Industry</h4>
                                    <p class="text-gray-900">
                                        @if($viewingCheatSheet->jobPosting)
                                            @switch($viewingCheatSheet->jobPosting->company)
                                                @case('Google')
                                                    Technology / Internet Services
                                                    @break
                                                @case('Amazon')
                                                    Technology / E-commerce
                                                    @break
                                                @case('Stripe')
                                                    Technology / Financial Services
                                                    @break
                                                @default
                                                    Technology
                                            @endswitch
                                        @else
                                            Technology
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Company Size</h4>
                                    <p class="text-gray-900">
                                        @if($viewingCheatSheet->jobPosting)
                                            @switch($viewingCheatSheet->jobPosting->company)
                                                @case('Google')
                                                    150,000+ employees
                                                    @break
                                                @case('Amazon')
                                                    1,500,000+ employees
                                                    @break
                                                @case('Stripe')
                                                    7,000+ employees
                                                    @break
                                                @default
                                                    10,000+ employees
                                            @endswitch
                                        @else
                                            10,000+ employees
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Key Competitors</h4>
                                <div class="flex flex-wrap gap-2">
                                    @if($viewingCheatSheet->jobPosting)
                                        @switch($viewingCheatSheet->jobPosting->company)
                                            @case('Google')
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Microsoft</span>
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Amazon</span>
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Meta</span>
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Apple</span>
                                                @break
                                            @case('Amazon')
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Google</span>
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Microsoft</span>
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Alibaba</span>
                                                @break
                                            @case('Stripe')
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">PayPal</span>
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Square</span>
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Adyen</span>
                                                @break
                                        @endswitch
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Industry Leaders</span>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Emerging Companies</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Recent News & Updates</h4>
                                <ul class="space-y-2">
                                    @if($viewingCheatSheet->jobPosting)
                                        @switch($viewingCheatSheet->jobPosting->company)
                                            @case('Google')
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Launched new AI-powered search features with Gemini integration</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Expanded cloud infrastructure across Asia-Pacific region</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Announced commitment to carbon-neutral operations by 2030</span>
                                                </li>
                                                @break
                                            @case('Amazon')
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Expanded AWS services with new AI and machine learning capabilities</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Launched new fulfillment centers across multiple regions</span>
                                                </li>
                                                @break
                                            @case('Stripe')
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Expanded payment processing to 40+ new countries</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Launched new fraud prevention and risk management tools</span>
                                                </li>
                                                @break
                                        @endswitch
                                    @else
                                        <li class="flex items-start">
                                            <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                            <span class="text-gray-700">Industry trends and best practices for interview preparation</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                            <span class="text-gray-700">Updated interview techniques and evaluation criteria</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @elseif($activeViewTab === 'talking')
                        <!-- Talking Points Tab -->
                        <div class="space-y-6">
                            @if($viewingCheatSheet->key_points)
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Key Talking Points</h4>
                                    <div class="space-y-3">
                                        @if(is_array($viewingCheatSheet->key_points))
                                            @foreach($viewingCheatSheet->key_points as $point)
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <p class="text-gray-700">{{ $point }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-gray-700">{{ $viewingCheatSheet->key_points }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($viewingCheatSheet->examples)
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Examples & Stories</h4>
                                    <div class="space-y-4">
                                        @if(is_array($viewingCheatSheet->examples))
                                            @foreach($viewingCheatSheet->examples as $example)
                                                <div class="bg-gray-50 rounded-lg p-4">
                                                    <p class="text-gray-700">{{ $example }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <p class="text-gray-700">{{ $viewingCheatSheet->examples }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($viewingCheatSheet->suggested_response)
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Suggested Response Framework</h4>
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $viewingCheatSheet->suggested_response }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif($activeViewTab === 'topics')
                        <!-- Interview Topics Tab -->
                        <div class="space-y-6">
                            @if($viewingCheatSheet->follow_up_questions)
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Interview Questions</h4>
                                    <div class="space-y-4">
                                        @if(is_array($viewingCheatSheet->follow_up_questions))
                                            @foreach($viewingCheatSheet->follow_up_questions as $question)
                                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                                    <p class="text-gray-700 font-medium">{{ $question }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                                <p class="text-gray-700 font-medium">{{ $viewingCheatSheet->follow_up_questions }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Category</h4>
                                    <p class="text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $viewingCheatSheet->category)) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Practice Count</h4>
                                    <p class="text-gray-900 font-medium">{{ $viewingCheatSheet->usage_count }} times</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Average Score</h4>
                                    <p class="text-gray-900 font-medium">
                                        {{ $viewingCheatSheet->average_score ? number_format($viewingCheatSheet->average_score, 1) . '/10' : 'Not practiced' }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Last Practiced</h4>
                                    <p class="text-gray-900 font-medium">
                                        {{ $viewingCheatSheet->last_practiced_at ? $viewingCheatSheet->last_practiced_at->diffForHumans() : 'Never' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
