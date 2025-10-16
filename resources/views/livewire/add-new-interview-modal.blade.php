<div>
@if($showModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div wire:click="closeModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-600 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Add New Interview</h3>
                            <p class="text-gray-600 mt-1">Set up a practice session for an upcoming interview</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-8 py-6">
                
                <!-- Job Posting URL Section -->
                <div class="mb-8">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">Job Posting URL</h4>
                    <div class="flex gap-3">
                        <input 
                            type="url" 
                            wire:model="jobPostingUrl" 
                            placeholder="https://company.com/careers/job-posting"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        />
                        <button 
                            wire:click="analyzeJobPosting"
                            wire:loading.attr="disabled"
                            class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors disabled:opacity-50"
                        >
                            <span wire:loading.remove>Analyze</span>
                            <span wire:loading>Analyzing...</span>
                        </button>
                    </div>
                    <p class="text-gray-600 text-sm mt-2">We'll analyze the job posting to create personalized interview questions</p>
                </div>

                <!-- Resume Selection Section -->
                <div class="mb-8">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">Select Resume</h4>
                    
                    <!-- Use existing resume option -->
                    <div class="mb-4">
                        <label class="flex items-center gap-2 text-gray-700">
                            <input 
                                type="radio" 
                                wire:model="uploadNewResume" 
                                value="false"
                                class="text-purple-600 focus:ring-purple-500"
                            />
                            <span class="font-medium">Use existing resume</span>
                        </label>
                    </div>

                    <!-- Existing Resumes List -->
                    @if(count($userResumes) > 0 && !$uploadNewResume)
                        <div class="space-y-4 mb-6">
                            @foreach($userResumes as $index => $resume)
                                @php
                                    $match = collect($resumeMatches)->firstWhere('resume.id', $resume['id']);
                                    $matchData = $match ?: ['match_score' => 0, 'match_level' => ['label' => 'No Analysis', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'document', 'icon_color' => 'text-gray-600'], 'matching_keywords' => []];
                                @endphp
                                
                                <div 
                                    wire:click="selectResume({{ $resume['id'] }})"
                                    class="border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-300 hover:shadow-md transition-all {{ $selectedResumeId == $resume['id'] ? 'border-purple-500 bg-purple-50' : '' }}"
                                >
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4 flex-1">
                                            <!-- File Icon -->
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <!-- Resume Info -->
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-900 mb-1">{{ $resume['title'] }}</h5>
                                                <p class="text-gray-600 text-sm mb-2">{{ $resume['filename'] }}</p>
                                                <p class="text-gray-500 text-xs mb-3">Uploaded {{ $resume['uploaded_at']->format('M j, Y') }} • {{ $this->formatFileSize($resume['file_size']) }}</p>
                                                
                                                @if($matchData['match_score'] > 0)
                                                    <div class="flex items-center gap-2 mb-2">
                                                        @if($matchData['match_level']['icon'] == 'star')
                                                            <svg class="w-4 h-4 {{ $matchData['match_level']['icon_color'] }}" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                        @elseif($matchData['match_level']['icon'] == 'wave')
                                                            <svg class="w-4 h-4 {{ $matchData['match_level']['icon_color'] }}" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M3,12C3,12 5.2,6 12,6C18.8,6 21,12 21,12C21,12 18.8,18 12,18C5.2,18 3,12 3,12Z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 {{ $matchData['match_level']['icon_color'] }}" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                            </svg>
                                                        @endif
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $matchData['match_level']['color'] }}">
                                                            {{ $matchData['match_score'] }}% Match
                                                        </span>
                                                    </div>
                                                    
                                                    @if(count($matchData['matching_keywords']) > 0)
                                                        <div class="flex flex-wrap gap-1">
                                                            <span class="text-xs text-gray-600">{{ $matchData['match_level']['label'] }}:</span>
                                                            @foreach($matchData['matching_keywords'] as $keyword)
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                                    {{ $keyword }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Selection Indicator -->
                                        @if($selectedResumeId == $resume['id'])
                                            <div class="flex-shrink-0">
                                                <div class="w-6 h-6 bg-purple-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Upload new resume option -->
                    <div class="mb-4">
                        <label class="flex items-center gap-2 text-gray-700">
                            <input 
                                type="radio" 
                                wire:model="uploadNewResume" 
                                value="true"
                                class="text-purple-600 focus:ring-purple-500"
                            />
                            <span class="font-medium">Upload new resume</span>
                        </label>
                    </div>

                    @if($uploadNewResume)
                        <div class="border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center justify-center w-full">
                                <label for="newResumeFile" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX (MAX. 10MB)</p>
                                    </div>
                                    <input 
                                        id="newResumeFile" 
                                        type="file" 
                                        wire:model="newResumeFile" 
                                        class="hidden" 
                                        accept=".pdf,.doc,.docx"
                                    />
                                </label>
                            </div>
                            
                            @if($newResumeFile)
                                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        <span class="text-sm text-green-800 font-medium">File selected: {{ $newResumeFile->getClientOriginalName() }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button 
                    wire:click="closeModal"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    wire:click="startInterviewPractice"
                    class="px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors"
                >
                    Start Interview Practice
                </button>
            </div>
        </div>
    </div>
</div>
@else
<!-- Modal not shown - hidden content to satisfy Livewire root tag requirement -->
@endif
</div>
