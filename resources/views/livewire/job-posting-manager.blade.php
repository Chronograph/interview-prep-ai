<div class="space-y-8">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50/80 dark:bg-green-900/30 backdrop-blur-xl border border-green-200/50 dark:border-green-700/50 text-green-800 dark:text-green-200 px-6 py-4 rounded-2xl shadow-lg" role="alert">
            <div class="flex items-center gap-3">
                <div class="p-1 bg-green-500 rounded-full">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50/80 dark:bg-red-900/30 backdrop-blur-xl border border-red-200/50 dark:border-red-700/50 text-red-800 dark:text-red-200 px-6 py-4 rounded-2xl shadow-lg" role="alert">
            <div class="flex items-center gap-3">
                <div class="p-1 bg-red-500 rounded-full">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Job Postings</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your job opportunities</p>
            </div>
        </div>
        <button wire:click="openCreateModal" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Job Posting
        </button>
    </div>

    <!-- Job Postings List -->
    @if(count($jobPostings) > 0)
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($jobPostings as $jobPosting)
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $jobPosting['title'] }}</h3>
                            <p class="text-gray-700 dark:text-gray-300 font-semibold mb-3">{{ $jobPosting['company'] }}</p>
                            @if($jobPosting['location'])
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="p-1 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $jobPosting['location'] }}</p>
                                </div>
                            @endif
                            @if($jobPosting['salary_range'])
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="p-1 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $jobPosting['salary_range'] }}</p>
                                </div>
                            @endif
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full backdrop-blur-sm
                                @if($jobPosting['employment_type'] === 'full-time') bg-green-100/80 text-green-800 border border-green-200/50
                                @elseif($jobPosting['employment_type'] === 'part-time') bg-blue-100/80 text-blue-800 border border-blue-200/50
                                @elseif($jobPosting['employment_type'] === 'contract') bg-yellow-100/80 text-yellow-800 border border-yellow-200/50
                                @elseif($jobPosting['employment_type'] === 'internship') bg-purple-100/80 text-purple-800 border border-purple-200/50
                                @else bg-gray-100/80 text-gray-800 border border-gray-200/50
                                @endif">
                                {{ ucfirst(str_replace('-', ' ', $jobPosting['employment_type'])) }}
                            </span>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button wire:click="openEditModal({{ $jobPosting['id'] }})" class="p-2 text-blue-600 hover:text-blue-800 bg-blue-50/80 hover:bg-blue-100/80 backdrop-blur-sm rounded-xl border border-blue-200/50 transition-all duration-200 transform hover:scale-110">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="deleteJobPosting({{ $jobPosting['id'] }})" wire:confirm="Are you sure you want to delete this job posting?" class="p-2 text-red-600 hover:text-red-800 bg-red-50/80 hover:bg-red-100/80 backdrop-blur-sm rounded-xl border border-red-200/50 transition-all duration-200 transform hover:scale-110">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        <p class="line-clamp-3 leading-relaxed">{{ $jobPosting['description'] }}</p>
                    </div>
                    @if($jobPosting['requirements'])
                        <div class="mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-600/50">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="p-1 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Requirements</p>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ $jobPosting['requirements'] }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 text-center py-16 px-8">
            <div class="p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mx-auto w-fit mb-6">
                <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">No job postings yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">Start building your interview preparation by adding job postings that match your career goals.</p>
            <button wire:click="openCreateModal" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 flex items-center gap-3 mx-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Your First Job Posting
            </button>
        </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" wire:click="closeCreateModal">
            <div class="relative top-20 mx-auto p-8 w-11/12 md:w-3/4 lg:w-1/2 max-w-4xl bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/20 dark:border-gray-700/50" wire:click.stop>
                <div class="">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Add New Job Posting</h3>
                        </div>
                        <button wire:click="closeCreateModal" class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 bg-gray-100/80 dark:bg-gray-700/80 hover:bg-gray-200/80 dark:hover:bg-gray-600/80 backdrop-blur-sm rounded-xl transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Upload Job Posting (Optional)</label>
                        <div class="relative">
                            <input type="file" wire:model="file" accept=".pdf,.doc,.docx,.txt" class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-blue-600 file:to-indigo-600 file:text-white hover:file:from-blue-700 hover:file:to-indigo-700 file:transition-all file:duration-200 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl px-4 py-3">
                        </div>
                        @error('file') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                        
                        @if($isUploading)
                            <div class="mt-4">
                                <div class="bg-blue-200/50 dark:bg-blue-900/30 rounded-full h-3 backdrop-blur-sm">
                                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-3 rounded-full transition-all duration-300 shadow-sm" style="width: {{ $uploadProgress }}%"></div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 font-medium">Parsing job posting... {{ $uploadProgress }}%</p>
                            </div>
                        @endif
                    </div>

                    <form wire:submit.prevent="createJobPosting" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Title *</label>
                                <input type="text" wire:model="title" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @error('title') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Company *</label>
                                <input type="text" wire:model="company" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @error('company') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                <input type="text" wire:model="location" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @error('location') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Salary Range</label>
                                <input type="text" wire:model="salary_range" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100" placeholder="e.g., $80,000 - $120,000">
                                @error('salary_range') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Employment Type *</label>
                            <select wire:model="employment_type" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                            @error('employment_type') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Description *</label>
                            <textarea wire:model="description" rows="4" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100 resize-none"></textarea>
                            @error('description') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Requirements</label>
                            <textarea wire:model="requirements" rows="3" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100 resize-none"></textarea>
                            @error('requirements') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end space-x-4 pt-6">
                            <button type="button" wire:click="closeCreateModal" class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100/80 dark:bg-gray-700/80 hover:bg-gray-200/80 dark:hover:bg-gray-600/80 backdrop-blur-sm rounded-xl border border-gray-200/50 dark:border-gray-600/50 transition-all duration-200 transform hover:scale-105">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                Create Job Posting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" wire:click="closeEditModal">
            <div class="relative top-20 mx-auto p-8 w-11/12 md:w-3/4 lg:w-1/2 max-w-4xl bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/20 dark:border-gray-700/50" wire:click.stop>
                <div class="">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Edit Job Posting</h3>
                        </div>
                        <button wire:click="closeEditModal" class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 bg-gray-100/80 dark:bg-gray-700/80 hover:bg-gray-200/80 dark:hover:bg-gray-600/80 backdrop-blur-sm rounded-xl transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="updateJobPosting" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Title *</label>
                                <input type="text" wire:model="title" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @error('title') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Company *</label>
                                <input type="text" wire:model="company" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @error('company') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                <input type="text" wire:model="location" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @error('location') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Salary Range</label>
                                <input type="text" wire:model="salary_range" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @error('salary_range') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Employment Type *</label>
                            <select wire:model="employment_type" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100">
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                            @error('employment_type') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Description *</label>
                            <textarea wire:model="description" rows="4" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100 resize-none"></textarea>
                            @error('description') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Requirements</label>
                            <textarea wire:model="requirements" rows="3" class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-200 text-gray-900 dark:text-gray-100 resize-none"></textarea>
                            @error('requirements') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end space-x-4 pt-6">
                            <button type="button" wire:click="closeEditModal" class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100/80 dark:bg-gray-700/80 hover:bg-gray-200/80 dark:hover:bg-gray-600/80 backdrop-blur-sm rounded-xl border border-gray-200/50 dark:border-gray-600/50 transition-all duration-200 transform hover:scale-105">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                Update Job Posting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
