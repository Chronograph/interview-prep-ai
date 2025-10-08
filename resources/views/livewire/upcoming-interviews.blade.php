<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Upcoming Interviews</h1>
                    <p class="mt-2 text-lg text-gray-600">Track and prepare for your scheduled interviews.</p>
                </div>
                <x-button wire:click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white" icon="plus">
                    Add Interview
                </x-button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Interviews List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @if($interviews->count() > 0)
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($interviews as $interview)
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
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming interviews</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by scheduling your first interview.</p>
                    <div class="mt-6">
                        <x-button wire:click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white" icon="plus">
                            Add Interview
                        </x-button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Interview Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Schedule Interview</h3>
                        <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="createInterview">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Interview Title</label>
                                <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Company</label>
                                    <input type="text" wire:model="company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('company') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Position</label>
                                    <input type="text" wire:model="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" wire:model="interview_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('interview_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Time</label>
                                    <input type="time" wire:model="interview_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('interview_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Interview Type</label>
                                <select wire:model="interview_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="mixed">Mixed</option>
                                    <option value="technical">Technical</option>
                                    <option value="behavioral">Behavioral</option>
                                </select>
                                @error('interview_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location (if on-site)</label>
                                <input type="text" wire:model="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Readiness Score</label>
                                <input type="range" min="0" max="100" wire:model="readiness_score" class="mt-1 block w-full">
                                <div class="text-sm text-gray-500">{{ $readiness_score }}%</div>
                                @error('readiness_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 mt-6">
                            <x-button wire:click="closeCreateModal" secondary>
                                Cancel
                            </x-button>
                            <x-button type="submit" primary>
                                Schedule Interview
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Interview Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Edit Interview</h3>
                        <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="updateInterview">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Interview Title</label>
                                <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Company</label>
                                    <input type="text" wire:model="company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('company') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Position</label>
                                    <input type="text" wire:model="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" wire:model="interview_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('interview_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Time</label>
                                    <input type="time" wire:model="interview_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('interview_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Interview Type</label>
                                <select wire:model="interview_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="mixed">Mixed</option>
                                    <option value="technical">Technical</option>
                                    <option value="behavioral">Behavioral</option>
                                </select>
                                @error('interview_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location (if on-site)</label>
                                <input type="text" wire:model="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Readiness Score</label>
                                <input type="range" min="0" max="100" wire:model="readiness_score" class="mt-1 block w-full">
                                <div class="text-sm text-gray-500">{{ $readiness_score }}%</div>
                                @error('readiness_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <x-button wire:click="deleteInterview({{ $editingInterview->id }})"
                                class="bg-red-600 hover:bg-red-700 text-white"
                                icon="trash">
                                Delete
                            </x-button>
                            <div class="flex items-center space-x-3">
                                <x-button wire:click="closeEditModal" secondary>
                                    Cancel
                                </x-button>
                                <x-button type="submit" primary>
                                    Update Interview
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
    @endif
</div>
