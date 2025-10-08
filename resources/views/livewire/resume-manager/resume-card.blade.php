@php
    $metrics = $resume->getPerformanceMetrics();
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $resume->title }}</h3>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Updated {{ $resume->updated_at->format('Y-m-d') }}
                    </span>
                    <span>v{{ $resume->version }}</span>
                    <span>{{ $resume->formatted_file_size }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-md">
                {{ $resume->optimization_score }}% Optimized
            </span>

            <!-- Dropdown Menu -->
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button class="p-2 hover:bg-gray-200 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </x-slot>

                <x-dropdown.item icon="pencil" label="Edit" wire:click="openEditModal({{ $resume->id }})" />
                <x-dropdown.item icon="document-duplicate" label="Create Version" wire:click="createVersion({{ $resume->id }})" />
                <x-dropdown.item separator />
                <x-dropdown.item icon="trash" label="Delete Resume" wire:click="deleteResume({{ $resume->id }})" wire:confirm="Are you sure you want to delete this resume?" />
            </x-dropdown>
        </div>
    </div>

    <!-- Optimized For Section -->
    @if($resume->optimized_companies || $resume->optimized_roles)
        <div class="mb-4 space-y-2">
            <p class="text-sm font-medium text-gray-700">Optimized for:</p>

            @if($resume->optimized_companies && count($resume->optimized_companies) > 0)
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Companies:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($resume->optimized_companies as $company)
                            <span class="px-2 py-1 bg-white border border-gray-300 rounded text-sm text-gray-800">{{ $company }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($resume->optimized_roles && count($resume->optimized_roles) > 0)
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Roles:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($resume->optimized_roles as $role)
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-md text-sm">{{ $role }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Performance Metrics -->
    <div class="bg-gray-50 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Performance
            </h4>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View Applications</a>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <div class="flex items-center gap-2 text-gray-600 text-sm mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Applications
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $metrics['applications'] }}</div>
            </div>
            <div>
                <div class="flex items-center gap-2 text-gray-600 text-sm mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Response Rate
                </div>
                <div class="text-2xl font-bold text-green-600">{{ $metrics['response_rate'] }}%</div>
            </div>
            <div>
                <div class="flex items-center gap-2 text-gray-600 text-sm mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Interviews
                </div>
                <div class="text-2xl font-bold text-blue-600">{{ $metrics['interviews'] }}</div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3">
        <x-button primary icon="sparkles">
            Get Recommendations
        </x-button>
        @if($resume->file_path)
            <a href="{{ Storage::url($resume->file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Preview
            </a>
        @endif
        <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Applications ({{ $metrics['applications'] }})
        </button>
    </div>
</div>
