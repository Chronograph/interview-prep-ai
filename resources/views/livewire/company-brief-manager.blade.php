<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Company Research</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Research and prepare for company interviews</p>
            </div>
        </div>
        <x-button wire:click="openCreateModal" primary icon="plus" size="lg">
            Research Company
        </x-button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-50/80 dark:bg-green-900/30 backdrop-blur-xl border border-green-200/50 dark:border-green-700/50 text-green-800 dark:text-green-200 px-6 py-4 rounded-2xl shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50/80 dark:bg-red-900/30 backdrop-blur-xl border border-red-200/50 dark:border-red-700/50 text-red-800 dark:text-red-200 px-6 py-4 rounded-2xl shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search/Filter Controls -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 p-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="search"
                           type="text"
                           placeholder="Search companies..."
                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm">
                </div>
            </div>

            <!-- Industry Filter -->
            <div class="md:w-48">
                <select wire:model.live="industry"
                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm">
                    <option value="">All Industries</option>
                    <option value="technology">Technology</option>
                    <option value="finance">Finance</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="retail">Retail</option>
                    <option value="manufacturing">Manufacturing</option>
                    <option value="consulting">Consulting</option>
                    <option value="government">Government</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Company Briefs List -->
    @if($briefs && count($briefs) > 0)
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($briefs as $brief)
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $brief->company_name }}</h3>
                            @if($brief->industry)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-800 dark:text-indigo-200 mt-1">
                                    {{ ucfirst($brief->industry) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <x-button wire:click="openEditModal({{ $brief->id }})" size="xs" primary icon="pencil" />
                            <x-button wire:click="refreshBrief({{ $brief->id }})" size="xs" success icon="arrow-path" />
                            <x-button wire:click="openDeleteModal({{ $brief->id }})" size="xs" negative icon="trash" />
                        </div>
                    </div>

                    <!-- Company Description -->
                    @if($brief->company_description)
                        <div class="mb-4">
                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $brief->company_description }}</p>
                        </div>
                    @endif

                    <!-- Key Products/Services -->
                    @if($brief->key_products_services && count($brief->key_products_services) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Key Offerings</h4>
                            <div class="flex flex-wrap gap-1">
                                @foreach($brief->key_products_services as $product)
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-800 dark:text-blue-200">
                                        {{ $product }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Talking Points Preview -->
                    @if($brief->talking_points && count($brief->talking_points) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Key Talking Points</h4>
                            <div class="space-y-1">
                                @foreach(array_slice($brief->talking_points, 0, 3) as $point)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 bg-gray-50/80 dark:bg-gray-700/50 rounded-lg p-2">{{ Str::limit($point, 60) }}</p>
                                @endforeach
                                @if(count($brief->talking_points) > 3)
                                    <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">+{{ count($brief->talking_points) - 3 }} more points</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Footer -->
                    <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-200/50 dark:border-gray-700/30">
                        <span class="text-gray-500 dark:text-gray-400">
                            Updated {{ $brief->last_updated_at ? $brief->last_updated_at->format('M j, Y') : $brief->updated_at->format('M j, Y') }}
                        </span>
                        @if($brief->potential_questions && count($brief->potential_questions) > 0)
                            <span class="inline-flex items-center gap-1 text-indigo-600 dark:text-indigo-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                {{ count($brief->potential_questions) }} Qs
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 text-center py-16 px-8">
            <div class="p-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mx-auto w-fit mb-6">
                <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 00-2 2H6a2 2 0 00-2-2V8.1m8-.1c0 .11-.294.8-.8.8h-.8V7a1 1 0 00-1-1H9.01a1 1 0 00-1 1v.8c-.506 0-.8.69-.8.8m6.8 4.6V17a2 2 0 01-2 2H9a2 2 0 01-2-2v-2.4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">No company research yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">Start researching companies to get insider knowledge for your interviews.</p>
            <x-button wire:click="openCreateModal" primary size="lg" icon="plus">
                Research Your First Company
            </x-button>
        </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="closeCreateModal"></div>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Research Company</h3>
                        </div>

                        <form wire:submit="createBrief" class="space-y-6">
                            <!-- Company Name -->
                            <div class="space-y-2">
                                <label for="company_name" class="block text-sm font-semibold text-gray-900 dark:text-gray-100">Company Name</label>
                                <input wire:model="company_name"
                                       type="text"
                                       id="company_name"
                                       class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                       placeholder="e.g., Google, Microsoft, Tesla"
                                       required>
                            </div>

                            <!-- Job Posting (Optional) -->
                            <div class="space-y-2">
                                <label for="job_posting_id" class="block text-sm font-semibold text-gray-900 dark:text-gray-100">Related Job Posting (Optional)</label>
                                <select wire:model="job_posting_id"
                                        id="job_posting_id"
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100">
                                    <option value="">Select Job Posting...</option>
                                    @foreach($jobPostings as $job)
                                        <option value="{{ $job['id'] }}">{{ $job['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Additional Context -->
                            <div class="space-y-2">
                                <label for="additional_context" class="block text-sm font-semibold text-gray-900 dark:text-gray-100">Additional Context (Optional)</label>
                                <textarea wire:model="additional_context"
                                          id="additional_context"
                                          rows="3"
                                          class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                          placeholder="Any additional information that might help with the research..."></textarea>
                            </div>

                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200/50 dark:border-gray-700/30">
                                <x-button type="button" wire:click="closeCreateModal" secondary>
                                    Cancel
                                </x-button>
                                <x-button type="submit" wire:loading.attr="disabled" primary>
                                    Research Company
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="closeEditModal"></div>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Edit Company Research</h3>
                        </div>

                        <form wire:submit="updateBrief" class="space-y-6">
                            <!-- Talking Points -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Talking Points</h4>
                                @foreach($talking_points as $index => $point)
                                    <div class="flex gap-2">
                                        <input wire:model="talking_points.{{ $index }}"
                                               type="text"
                                               class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                               placeholder="Add talking point...">
                                        @if(count($talking_points) > 1)
                                            <x-button wire:click="removeTalkingPoint({{ $index }})" type="button" size="xs" negative>
                                                Remove
                                            </x-button>
                                        @endif
                                    </div>
                                @endforeach
                                <x-button wire:click="addTalkingPoint" type="button" size="sm" primary icon="plus">
                                    Add Talking Point
                                </x-button>
                            </div>

                            <!-- Notes -->
                            <div class="space-y-2">
                                <label for="notes" class="block text-sm font-semibold text-gray-900 dark:text-gray-100">Notes</label>
                                <textarea wire:model="notes"
                                          id="notes"
                                          rows="4"
                                          class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                          placeholder="Your personal notes..."></textarea>
                            </div>

                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200/50 dark:border-gray-700/30">
                                <x-button type="button" wire:click="closeEditModal" secondary>
                                    Cancel
                                </x-button>
                                <x-button type="submit" wire:loading.attr="disabled" primary>
                                    Update Research
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="closeDeleteModal"></div>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent">Delete Company Research</h3>
                        </div>

                        <p class="text-gray-600 dark:text-gray-400 mb-8">Are you sure you want to delete this company research?</p>

                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200/50 dark:border-gray-700/30">
                            <x-button wire:click="closeDeleteModal" secondary>
                                Cancel
                            </x-button>
                            <x-button wire:click="deleteBrief" negative>
                                Delete Research
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

