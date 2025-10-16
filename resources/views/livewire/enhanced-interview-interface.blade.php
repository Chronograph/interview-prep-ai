<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-purple-900">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Interview Readiness Dashboard -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                    Interview Readiness
                </h1>
                <button wire:click="completeSession" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                    Complete Session
                </button>
            </div>

            <!-- Interview Details -->
            <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50/50 dark:from-gray-700/50 dark:to-blue-900/20 rounded-xl border border-gray-200 dark:border-gray-600">
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Company</div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">General Practice</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Role</div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">Interview Practice</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Difficulty</div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100 capitalize">{{ $session->difficulty_level }} ({{ $totalQuestions }} questions)</div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overall Progress</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $overallScore }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                    <div class="bg-gradient-to-r from-purple-500 to-blue-600 h-3 rounded-full transition-all duration-500" 
                         style="width: {{ $overallScore }}%"></div>
                </div>
            </div>

            <!-- Readiness Status -->
            <div class="flex items-center gap-4 mb-6">
                <div class="flex-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $completedQuestions }}/{{ $totalQuestions }} Completed</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ number_format($averageScore, 1) }}/10 Avg Score</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $bestScore }}/10 Best Score</div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($overallScore >= 80) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($overallScore >= 60) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                    @if($overallScore >= 80) Ready
                    @elseif($overallScore >= 60) Almost Ready
                    @else Needs Practice
                    @endif
                </div>
            </div>

            <!-- Strong Areas & Focus Areas -->
            <div class="grid md:grid-cols-2 gap-6">
                @if(count($strongAreas) > 0)
                <div>
                    <h3 class="text-lg font-semibold text-green-700 dark:text-green-400 mb-3">Strong Areas</h3>
                    <ul class="space-y-2">
                        @foreach($strongAreas as $area)
                        <li class="text-green-600 dark:text-green-400 text-sm">✓ {{ $area }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(count($focusAreas) > 0)
                <div>
                    <h3 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-3">Focus Areas</h3>
                    <ul class="space-y-2">
                        @foreach($focusAreas as $area)
                        <li class="text-red-600 dark:text-red-400 text-sm">⚠ {{ $area }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <!-- Question Navigation -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 mb-8">
            <div class="flex items-center justify-center gap-2 flex-wrap">
                @foreach($questions as $index => $question)
                <button wire:click="setCurrentQuestion({{ $index }})" 
                        class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300
                        @if($index === $currentQuestionIndex) bg-gradient-to-r from-purple-500 to-blue-600 text-white shadow-lg
                        @elseif($this->getQuestionStatus($question) === 'needs_improvement') bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900 dark:text-red-200
                        @elseif($this->getQuestionStatus($question) === 'excellent') bg-green-100 text-green-600 hover:bg-green-200 dark:bg-green-900 dark:text-green-200
                        @elseif($this->getQuestionStatus($question) === 'good') bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200
                        @else bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 @endif">
                    {{ $index + 1 }}
                    @if($this->getQuestionStatus($question) === 'needs_improvement')
                        <svg class="w-3 h-3 absolute -top-1 -right-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>
                @endforeach
            </div>
        </div>

        <!-- Main Interview Interface -->
        <div class="grid lg:grid-cols-2 gap-8">
            
            <!-- Video Player Section -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Video Recording</h3>
                    
                    <!-- Video Player -->
                    <div class="relative bg-gray-900 rounded-xl aspect-video mb-4">
                        <video id="video-preview" class="w-full h-full object-cover rounded-xl" autoplay muted></video>
                        
                        @if(!$isRecording)
                        <!-- Recording Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-8">
                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                </svg>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Recording Indicator -->
                        @if($isRecording)
                        <div class="absolute top-4 left-4 flex items-center gap-2 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                            REC {{ $this->formattedTime }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Recording Controls -->
                    <div class="flex justify-center gap-4">
                        @if(!$isRecording)
                        <button wire:click="startRecording" 
                                class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                                <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                            </svg>
                            Start Recording
                        </button>
                        @else
                        <button wire:click="stopRecording" 
                                class="px-8 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <rect x="6" y="6" width="12" height="12" rx="2"/>
                            </svg>
                            Stop Recording
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Question Details Section -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
                @if($currentQuestion)
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}
                    </h3>
                    <button wire:click="showQuestionHistory" 
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        History
                    </button>
                </div>

                <!-- Question Category & Difficulty -->
                <div class="flex gap-2 mb-4">
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm font-medium">
                        {{ ucfirst(str_replace('_', ' ', $currentQuestion['category'])) }}
                    </span>
                    <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded-full text-sm font-medium">
                        {{ ucfirst($currentQuestion['difficulty']) }}
                    </span>
                </div>

                <!-- Question Text -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50/50 dark:from-gray-800/50 dark:to-blue-900/20 rounded-xl p-6 mb-6">
                    <p class="text-gray-800 dark:text-gray-200 text-lg leading-relaxed">
                        {{ $currentQuestion['question'] }}
                    </p>
                </div>

                <!-- Response Guidelines -->
                <div class="grid grid-cols-3 gap-4 mb-6 text-center">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Recommended time</div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">3 minutes</div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Attempts</div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ count($currentQuestion['attempts'] ?? []) }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Best</div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ ($currentQuestion['best_score'] ?? 0) }}/10</div>
                    </div>
                </div>

                <!-- Text Response Input -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Response</label>
                    <textarea wire:model="currentResponse" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                              placeholder="Type your response here..."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    @if(count($currentQuestion['attempts'] ?? []) > 0)
                    <button wire:click="$set('showRetakeModal', true)" 
                            class="flex-1 px-4 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Retake
                    </button>
                    @endif
                    
                    <button wire:click="submitResponse" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
                        Submit Response
                    </button>
                </div>

                @else
                <div class="text-center py-12">
                    <div class="text-gray-500 dark:text-gray-400">No questions available</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Response History Modal -->
        @if($showHistory && $currentQuestion)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50" wire:click="$set('showHistory', false)">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden" wire:click.stop>
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Response History</h3>
                        <button wire:click="$set('showHistory', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6 overflow-y-auto max-h-96">
                    <div class="space-y-4">
                        @forelse($responseHistory as $attempt)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Attempt {{ $loop->iteration }} - {{ \Carbon\Carbon::parse($attempt['submitted_at'])->format('M j, Y g:i A') }}
                                </span>
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded text-sm font-medium">
                                    {{ $attempt['score'] }}/10
                                </span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 text-sm mb-2">{{ $attempt['response'] }}</p>
                            @if(isset($attempt['evaluation']['overall_feedback']))
                            <p class="text-gray-600 dark:text-gray-400 text-xs italic">{{ $attempt['evaluation']['overall_feedback'] }}</p>
                            @endif
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">No previous attempts</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Retake Modal -->
        @if($showRetakeModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50" wire:click="$set('showRetakeModal', false)">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4" wire:click.stop>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Retake Question</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to retake this question? This will clear your current response.</p>
                    <div class="flex gap-3">
                        <button wire:click="$set('showRetakeModal', false)" 
                                class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button wire:click="retakeQuestion" 
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Retake
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Complete Session Modal -->
        @if($showCompleteModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50" wire:click="$set('showCompleteModal', false)">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4" wire:click.stop>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Complete Interview Session</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to complete this interview session? You can always return to practice more.</p>
                    <div class="flex gap-3">
                        <button wire:click="$set('showCompleteModal', false)" 
                                class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Continue Practice
                        </button>
                        <button wire:click="confirmComplete" 
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Complete Session
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', function () {
    // Video recording functionality
    let mediaRecorder;
    let recordedChunks = [];
    
    Livewire.on('start-recording', function () {
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(function(stream) {
                const video = document.getElementById('video-preview');
                video.srcObject = stream;
                
                mediaRecorder = new MediaRecorder(stream);
                recordedChunks = [];
                
                mediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };
                
                mediaRecorder.onstop = function() {
                    const blob = new Blob(recordedChunks, { type: 'video/webm' });
                    Livewire.dispatch('recording-completed', { chunks: recordedChunks });
                };
                
                mediaRecorder.start();
            })
            .catch(function(error) {
                console.error('Error accessing media devices:', error);
                alert('Unable to access camera and microphone. Please check permissions.');
            });
    });
    
    Livewire.on('stop-recording', function () {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
    });
});
</script>
@endpush
