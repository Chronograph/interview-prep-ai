<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/30 p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Resume Manager</h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Manage and organize your professional resumes</p>
                </div>
            </div>
            <button 
                wire:click="openCreateModal" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-300/50"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <span>Upload Resume</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50/80 dark:bg-green-900/20 backdrop-blur-xl border border-green-200/50 dark:border-green-700/30 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-lg font-semibold bg-gradient-to-r from-green-700 to-emerald-700 bg-clip-text text-transparent">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Primary Resume Highlight -->
    @php
        $primaryResume = collect($resumes)->firstWhere('is_primary', true);
    @endphp
    @if($primaryResume)
        <div class="bg-gradient-to-br from-amber-50/80 to-yellow-50/80 dark:from-amber-900/20 dark:to-yellow-900/20 backdrop-blur-xl border border-amber-200/50 dark:border-amber-700/30 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-amber-700 to-yellow-700 bg-clip-text text-transparent">Primary Resume</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $primaryResume['title'] }}</h3>
            @if($primaryResume['summary'])
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $primaryResume['summary'] }}</p>
            @endif
        </div>
    @endif

    <!-- Resumes List -->
    @if(count($resumes) > 0)
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($resumes as $resume)
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-gray-200/50 dark:border-gray-700/30 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group {{ $resume['is_primary'] ? 'ring-2 ring-amber-500/50' : '' }}">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $resume['title'] }}</h3>
                                @if($resume['is_primary'])
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-amber-500 to-yellow-600 text-white shadow-lg">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        Primary
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                                Updated {{ \Carbon\Carbon::parse($resume['updated_at'])->format('M j, Y') }}
                            </p>
                        </div>
                        <div class="flex gap-1 ml-4">
                            @if(!$resume['is_primary'])
                                <button 
                                    wire:click="setPrimary({{ $resume['id'] }})"
                                    class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-200 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl"
                                    title="Set as Primary"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </button>
                            @endif
                            <button 
                                wire:click="openEditModal({{ $resume['id'] }})"
                                class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl"
                                title="Edit"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button 
                                wire:click="deleteResume({{ $resume['id'] }})"
                                wire:confirm="Are you sure you want to delete this resume?"
                                class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl"
                                title="Delete"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    @if($resume['summary'])
                        <div class="mb-4">
                            <p class="text-gray-700 dark:text-gray-300 text-sm line-clamp-3 leading-relaxed">{{ $resume['summary'] }}</p>
                        </div>
                    @endif
                    
                    @if($resume['skills'])
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                <div class="p-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Key Skills
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $skills = array_slice(explode(',', $resume['skills']), 0, 5);
                                    $totalSkills = count(explode(',', $resume['skills']));
                                @endphp
                                @foreach($skills as $skill)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-800 dark:text-indigo-200 border border-indigo-200/50 dark:border-indigo-700/30">
                                        {{ trim($skill) }}
                                    </span>
                                @endforeach
                                @if($totalSkills > 5)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 border border-gray-200/50 dark:border-gray-600/30">
                                        +{{ $totalSkills - 5 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-200/50 dark:border-gray-700/30">
                        <span class="text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            Created {{ \Carbon\Carbon::parse($resume['created_at'])->format('M j, Y') }}
                        </span>
                        @if($resume['file_path'])
                            <a 
                                href="{{ Storage::url($resume['file_path']) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors font-medium group"
                            >
                                <div class="p-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg group-hover:shadow-lg transition-shadow">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-gray-200/50 dark:border-gray-700/30 rounded-3xl p-12 shadow-lg max-w-md mx-auto">
                <div class="p-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg mx-auto w-fit mb-6">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-3">No Resumes Yet</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">Ready to showcase your skills? Upload your first resume and start building your professional profile.</p>
                <button 
                    wire:click="openCreateModal" 
                    class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50"
                >
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Upload Your First Resume
                </button>
            </div>
        </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="closeCreateModal"></div>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200/50 dark:border-gray-700/30">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Upload Resume</h3>
                        </div>
                        
                        <!-- File Upload -->
                        <div class="mb-8">
                            <label for="file" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                <div class="p-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                Resume File
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    wire:model="file"
                                    accept=".pdf,.doc,.docx,.txt"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-indigo-500 file:to-purple-600 file:text-white file:shadow-lg hover:file:shadow-xl file:transition-all file:duration-200 border border-gray-200/50 dark:border-gray-700/30 rounded-xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50"
                                />
                            </div>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                PDF, DOC, DOCX, or TXT files. AI will auto-parse the content.
                            </p>
                            @error('file') <span class="text-red-500 text-sm flex items-center gap-1 mt-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                            
                            @if($isUploading)
                                <div class="mt-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl border border-indigo-200/50 dark:border-indigo-700/30">
                                    <div class="bg-gradient-to-r from-indigo-200 to-purple-200 dark:from-indigo-800 dark:to-purple-800 rounded-full h-3 overflow-hidden">
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500 shadow-lg" 
                                             wire:style="{ width: '{{ $uploadProgress ?? 0 }}%' }"></div>
                                    </div>
                                    <p class="text-sm font-medium text-indigo-700 dark:text-indigo-300 mt-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Parsing resume...
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <form wire:submit="createResume" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="title" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Resume Title</label>
                                    <input 
                                        type="text" 
                                        wire:model="title"
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" 
                                        placeholder="e.g., Senior Software Engineer Resume"
                                        required 
                                    />
                                    @error('title') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="summary" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Professional Summary</label>
                                    <textarea 
                                        wire:model="summary"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Brief professional summary..."
                                    ></textarea>
                                    @error('summary') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="skills" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Skills</label>
                                    <textarea 
                                        wire:model="skills"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="JavaScript, Python, React, Node.js, etc."
                                    ></textarea>
                                    @error('skills') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="experience" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Work Experience</label>
                                    <textarea 
                                        wire:model="experience"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Previous work experience..."
                                    ></textarea>
                                    @error('experience') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="education" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Education</label>
                                    <textarea 
                                        wire:model="education"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Educational background..."
                                    ></textarea>
                                    @error('education') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="certifications" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Certifications</label>
                                    <textarea 
                                        wire:model="certifications"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Professional certifications..."
                                    ></textarea>
                                    @error('certifications') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200/50 dark:border-gray-700/30">
                                <button 
                                    type="button"
                                    wire:click="closeCreateModal"
                                    class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-xl bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/30 text-gray-700 dark:text-gray-300 shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/50"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span wire:loading.remove class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Upload Resume
                                    </span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Uploading...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="closeEditModal"></div>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200/50 dark:border-gray-700/30">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Edit Resume</h3>
                        </div>
                        
                        <form wire:submit="updateResume" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="edit_title" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Resume Title</label>
                                    <input 
                                        type="text" 
                                        wire:model="title"
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" 
                                        placeholder="e.g., Senior Software Engineer Resume"
                                        required 
                                    />
                                    @error('title') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="edit_summary" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Professional Summary</label>
                                    <textarea 
                                        wire:model="summary"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Brief professional summary..."
                                    ></textarea>
                                    @error('summary') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="edit_skills" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Skills</label>
                                    <textarea 
                                        wire:model="skills"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="JavaScript, Python, React, Node.js, etc."
                                    ></textarea>
                                    @error('skills') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="edit_experience" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Work Experience</label>
                                    <textarea 
                                        wire:model="experience"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Previous work experience..."
                                    ></textarea>
                                    @error('experience') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="edit_education" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Education</label>
                                    <textarea 
                                        wire:model="education"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Educational background..."
                                    ></textarea>
                                    @error('education') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="edit_certifications" class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Certifications</label>
                                    <textarea 
                                        wire:model="certifications"
                                        rows="3" 
                                        class="block w-full border border-gray-200/50 dark:border-gray-700/30 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/50 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Professional certifications..."
                                    ></textarea>
                                    @error('certifications') <span class="text-red-500 text-sm flex items-center gap-1 mt-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200/50 dark:border-gray-700/30">
                                <button 
                                    type="button"
                                    wire:click="closeEditModal"
                                    class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-xl bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/30 text-gray-700 dark:text-gray-300 shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/50"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span wire:loading.remove class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Update Resume
                                    </span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Updating...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
