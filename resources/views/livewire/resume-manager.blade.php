<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Resume Builder') }}</h1>
            <p class="text-gray-600">{{ __('Upload and manage multiple resume versions. Get AI-powered recommendations tailored to your target companies.') }}</p>
    </div>

    <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Resumes Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">{{ __('Total Resumes') }}</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
                <div class="text-4xl font-bold text-gray-900 mb-1">{{ $totalResumes }}</div>
                <p class="text-sm text-gray-500">{{ __('Active versions') }}</p>
        </div>

        <!-- Avg. Optimization Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">{{ __('Avg. Optimization') }}</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
                <div class="text-4xl font-bold text-gray-900 mb-1">{{ $avgOptimization }}%</div>
                <p class="text-sm text-green-600 font-medium">{{ __('+12% this month') }}</p>
        </div>

        <!-- Companies Targeted Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">{{ __('Companies Targeted') }}</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
                <div class="text-4xl font-bold text-gray-900 mb-1">{{ $companiesTargeted }}</div>
                <p class="text-sm text-gray-500">{{ __('Unique companies') }}</p>
        </div>
    </div>

    <!-- Upload Area -->
        <div
            x-data="{ dragging: false }"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false"
            :class="dragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-white'"
            class="rounded-xl border-2 border-dashed transition-colors duration-200 p-12 mb-8"
        >
            <div class="max-w-xl mx-auto text-center space-y-6">
            <!-- Upload Icon -->
                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
            </div>

            <!-- Upload Text -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('Upload New Resume') }}</h3>
                    <p class="text-gray-600">{{ __('Drag and drop your resume here, or click to browse') }}</p>
            </div>

            <!-- Choose File Button -->
                <div>
                    <button
                        x-on:click="$openModal('createResumeModal')"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                Choose File
                    </button>
                </div>

            <!-- File Format Info -->
                <p class="text-sm text-gray-500">{{ __('Supports PDF, DOC, DOCX • Max 10MB') }}</p>
        </div>
    </div>

    <!-- Your Resume Versions Section -->
    @php
        $groupedResumes = $this->getGroupedResumes();
    @endphp

    @if($groupedResumes->count() > 0)
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold text-gray-900">Your Resume Versions</h2>
                <span class="text-gray-600">{{ $resumes->count() }} Resumes</span>
            </div>

            <div class="space-y-6">
                @foreach($groupedResumes as $group)
                    @include('livewire.resume-manager.resume-group-card', ['group' => $group])
                @endforeach
            </div>
        </div>
    @endif

    <!-- Modals -->
    @include('livewire.resume-manager.create-modal')
    @include('livewire.resume-manager.edit-modal')
    </div>
</div>
