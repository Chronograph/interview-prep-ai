<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Mastery Topics</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Track and improve your technical skills</p>
            </div>
        </div>
        <button 
            wire:click="openCreateModal" 
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:from-indigo-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Topic
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Topics</dt>
                            <dd class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $stats['total_topics'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Active Topics</dt>
                            <dd class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">{{ $stats['active_topics'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Avg Mastery</dt>
                            <dd class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">{{ number_format($stats['average_mastery'], 1) }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Practiced</dt>
                            <dd class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ $stats['topics_practiced'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="mb-6 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Search topics..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <select 
                    wire:model.live="categoryFilter"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Difficulty</label>
                <select 
                    wire:model.live="difficultyFilter"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                >
                    <option value="">All Levels</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                <select 
                    wire:model.live="sortBy"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                >
                    <option value="name">Name</option>
                    <option value="category">Category</option>
                    <option value="difficulty_level">Difficulty</option>
                    <option value="mastery_level">Mastery Level</option>
                    <option value="created_at">Created Date</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Topics List -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-lg overflow-hidden">
        @if($topics->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50/80 dark:bg-gray-700/80">
                        <tr>
                            <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center space-x-1">
                                    <span>Name</span>
                                    @if($sortBy === 'name')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Difficulty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mastery</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($topics as $topic)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $topic->name }}</div>
                                        @if($topic->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $topic->description }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                        {{ $topic->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($topic->difficulty_level === 'beginner') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                        @elseif($topic->difficulty_level === 'intermediate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 @endif">
                                        {{ ucfirst($topic->difficulty_level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full" style="width: {{ $topic->mastery_level }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $topic->mastery_level }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        wire:click="toggleActive({{ $topic->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                            @if($topic->is_active) bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800/50
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800/50 @endif"
                                    >
                                        {{ $topic->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button 
                                            wire:click="openEditModal({{ $topic->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="delete({{ $topic->id }})"
                                            wire:confirm="Are you sure you want to delete this topic?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $topics->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No mastery topics</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first mastery topic.</p>
                <div class="mt-6">
                    <button 
                        wire:click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Topic
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gradient-to-br from-purple-900/50 to-blue-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
                    <form wire:submit="save">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $showCreateModal ? 'Create New Topic' : 'Edit Topic' }}
                            </h3>
                        </div>
                        
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                                <input 
                                    type="text" 
                                    wire:model="name" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Enter topic name"
                                >
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <textarea 
                                    wire:model="description" 
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Enter topic description"
                                ></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                                <input 
                                    type="text" 
                                    wire:model="category" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="e.g., Data Structures, Algorithms, System Design"
                                >
                                @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Difficulty Level *</label>
                                <select 
                                    wire:model="difficulty_level"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                >
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                                @error('difficulty_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    wire:model="is_active" 
                                    id="is_active"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                >
                                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                    Active (available for practice)
                                </label>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50/80 dark:bg-gray-800/80 flex justify-end space-x-3">
                            <button 
                                type="button" 
                                wire:click="closeModals"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                            >
                                {{ $showCreateModal ? 'Create Topic' : 'Update Topic' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>