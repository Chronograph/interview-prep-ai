<div class="max-w-4xl mx-auto p-6">
    <!-- Interview Header -->
    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/30 p-8 mb-8">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">{{ $jobPosting->title ?? 'Interview Session' }}</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-300 ml-11">{{ $jobPosting->company ?? 'General Interview' }}</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status</div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/50 dark:to-purple-900/50 text-blue-800 dark:text-blue-200 border border-blue-200/50 dark:border-blue-700/50">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></div>
                    {{ ucfirst($interview->status) }}
                </div>
            </div>
        </div>
    </div>

    @if($currentQuestion)
        <!-- Current Question -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/30 p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Current Question</h2>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-900/50 dark:to-teal-900/50 text-emerald-800 dark:text-emerald-200 border border-emerald-200/50 dark:border-emerald-700/50">
                    Question {{ $currentQuestion->question_order ?? 1 }}
                </span>
            </div>
            
            <div class="bg-gradient-to-r from-gray-50 to-blue-50/50 dark:from-gray-800/50 dark:to-blue-900/20 rounded-xl p-6 mb-8 border border-gray-200/50 dark:border-gray-700/50">
                <p class="text-gray-800 dark:text-gray-200 text-lg leading-relaxed font-medium">{{ $currentQuestion->question }}</p>
            </div>

            <!-- Recording Controls -->
            <div class="bg-gradient-to-r from-gray-50/80 to-purple-50/80 dark:from-gray-800/50 dark:to-purple-900/20 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3">
                            @if($isRecording)
                                <div class="relative">
                                    <div class="w-4 h-4 bg-red-500 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-4 h-4 bg-red-500 rounded-full animate-ping opacity-75"></div>
                                </div>
                            @else
                                <div class="w-4 h-4 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                            @endif
                            <div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $isRecording ? 'Recording in Progress' : 'Ready to Record' }}
                                </span>
                                @if($isRecording)
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        Duration: {{ gmdate('H:i:s', $recordingTime) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        @if(!$isRecording)
                            <button 
                                wire:click="startRecording"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Start Recording
                            </button>
                        @else
                            <button 
                                wire:click="stopRecording"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h6v4H9z"></path>
                                </svg>
                                Stop Recording
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Response Input -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <label for="response" class="text-lg font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Your Response
                    </label>
                </div>
                <textarea
                    id="response"
                    wire:model="response"
                    rows="6"
                    class="w-full px-4 py-3 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                    placeholder="Share your thoughts and insights here..."
                ></textarea>
                @error('response') 
                    <div class="mt-2 flex items-center gap-2 text-red-600 dark:text-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <button 
                    wire:click="completeInterview"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 text-gray-700 dark:text-gray-200 font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Complete Interview
                </button>
                
                <div class="flex gap-3">
                    <button 
                        wire:click="submitResponse"
                        wire:loading.attr="disabled"
                        @disabled($isSubmitting || strlen(trim($response)) < 10)
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="submitResponse">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" wire:loading wire:target="submitResponse">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="submitResponse">Submit Response</span>
                        <span wire:loading wire:target="submitResponse">Submitting...</span>
                    </button>
                    
                    <button 
                        wire:click="nextQuestion"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        Next Question
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- No Questions State -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/30 p-12 text-center">
            <div class="mb-6">
                <div class="inline-flex p-4 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg mb-6">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent mb-3">No Questions Available</h3>
            <p class="text-gray-600 dark:text-gray-300 text-lg max-w-md mx-auto leading-relaxed">This interview session doesn't have any questions configured yet. Please contact your interviewer or try again later.</p>
        </div>
    @endif

    <!-- Complete Interview Modal -->
    @if($showCompleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-3xl text-left overflow-hidden shadow-2xl border border-white/20 dark:border-gray-700/30 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="p-8">
                        <div class="text-center mb-6">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg mb-6">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-4">Complete Interview</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed text-center mb-8">
                            Are you sure you want to complete this interview? You won't be able to add more responses after this.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <button 
                                wire:click="closeModal"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 text-gray-700 dark:text-gray-200 font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300/50"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                            <button 
                                wire:click="confirmComplete"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-300/50"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Complete Interview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>
