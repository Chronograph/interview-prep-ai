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
                    
                    <!-- Recording Status -->
                    @if(!empty($recordedChunks))
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2 text-green-800">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="font-semibold">Processing Response...</span>
                        </div>
                        <p class="text-green-700 text-sm mt-1">Your video ({{ $this->formattedTime }}) is being analyzed and will be automatically submitted.</p>
                    </div>
                    @endif

                    <!-- Video Recording Interface -->
                    @if(!empty($lastVideoUrl))
                    <div class="mt-6">
                        <!-- Video Recording Header -->
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Video Recording</h3>
                        
                        <!-- Video Player Interface -->
                        <div class="relative bg-gray-900 rounded-lg overflow-hidden shadow-xl">
                            <video id="recorded-video" 
                                   class="w-full h-auto rounded-lg" 
                                   controls 
                                   preload="metadata">
                                <source src="{{ $lastVideoUrl }}" type="video/webm">
                                Your browser does not support the video tag.
                            </video>
                            
                            <!-- Video Overlay with Play Button (shown when video is paused) -->
                            <div id="video-overlay" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 rounded-lg cursor-pointer" onclick="toggleVideoPlayback()">
                                <div class="w-20 h-20 bg-white bg-opacity-90 rounded-full flex items-center justify-center hover:bg-opacity-100 transition-all duration-200 shadow-lg">
                                    <svg id="play-icon" class="w-10 h-10 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                    <svg id="pause-icon" class="w-10 h-10 text-gray-800 hidden" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video Controls and Info -->
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Duration: {{ $this->formattedTime }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Saved and Ready
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="downloadVideo()" 
                                        class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Download
                                </button>
                                <button onclick="shareVideo()" 
                                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg text-sm hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                    Share
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Question Details Section -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
                @if($currentQuestion)
                <!-- Tab Navigation -->
                <div class="flex mb-6 border-b border-gray-200 dark:border-gray-700">
                    <button wire:click="$set('activeTab', 'current')" 
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'current' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Current Recording
                    </button>
                    <button wire:click="$set('activeTab', 'history')" 
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 {{ $activeTab === 'history' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        History
                        @if(count($currentQuestion['attempts'] ?? []) > 0)
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-full px-2 py-0.5">
                            {{ count($currentQuestion['attempts']) }}
                        </span>
                        @endif
                    </button>
                </div>

                @if($activeTab === 'current')
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}
                    </h3>
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

                <!-- Video Response Instructions -->
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-1">Video Response Required</h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">Record your answer using the video interface above. Your response will be automatically submitted and analyzed once you stop recording.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    @if(count($currentQuestion['attempts'] ?? []) > 0)
                    <button wire:click="$set('showRetakeModal', true)" 
                            class="flex-1 px-4 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Retake Question
                    </button>
                    @endif
                    
                    <button wire:click="nextQuestion" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
                        Next Question
                    </button>
                </div>

                @elseif($activeTab === 'history')
                <!-- Recording History Tab -->
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                        Recording History
                    </h3>
                    
                    <!-- Summary Scores -->
                    @if(count($currentQuestion['attempts'] ?? []) > 0)
                    <div class="flex gap-6 mb-6">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Best Score</div>
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $currentQuestion['best_score'] ?? 0 }}/10</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Latest Score</div>
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ ($currentQuestion['attempts'][0]['score'] ?? 0) }}/10</div>
                        </div>
                    </div>

                    <!-- Individual Recording Entries -->
                    <div class="space-y-3">
                        @foreach($currentQuestion['attempts'] as $attempt)
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($attempt['submitted_at'])->format('M j, g:i A') }}
                                    </div>
                                    <div class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $attempt['duration'] ?? '2:15' }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(isset($attempt['improvement']) && $attempt['improvement'])
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    @endif
                                    <span class="text-lg font-bold {{ isset($attempt['improvement']) && $attempt['improvement'] ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">
                                        {{ $attempt['score'] }}/10
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($attempt['score'] / 10) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        No recording history yet. Start your first attempt above!
                    </div>
                    @endif
                </div>
                @endif

                @else
                <div class="text-center py-12">
                    <div class="text-gray-500 dark:text-gray-400">No questions available</div>
                </div>
                @endif
            </div>

            <!-- Response Analysis Section -->
            @if($currentQuestion && isset($currentQuestion['feedback']) && !empty($currentQuestion['feedback']))
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 mt-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Response Analysis</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">Overall Score:</span>
                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $currentQuestion['feedback']['overall_score'] ?? $currentQuestion['feedback']['score'] * 10 }}/100</span>
                        @php
                            $score = $currentQuestion['feedback']['overall_score'] ?? $currentQuestion['feedback']['score'] * 10;
                            $statusClass = $score >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                                          ($score >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 
                                          'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300');
                            $statusText = $score >= 80 ? 'Excellent' : ($score >= 60 ? 'Good' : 'Needs Improvement');
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Role-Specific Feedback -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Role-Specific Feedback</h4>
                        <div class="space-y-4">
                            @if(isset($currentQuestion['feedback']['role_specific_feedback']))
                                @foreach($currentQuestion['feedback']['role_specific_feedback'] as $feedback)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            @php
                                                $iconClass = $feedback['score'] >= 8 ? 'text-green-600' : ($feedback['score'] >= 6 ? 'text-yellow-600' : 'text-red-600');
                                                $icon = $feedback['score'] >= 8 ? '✓' : ($feedback['score'] >= 6 ? '!' : '✗');
                                            @endphp
                                            <span class="text-xl {{ $iconClass }}">{{ $icon }}</span>
                                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $feedback['category'] }}</h5>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $feedback['score'] }}/10</span>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-3">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $feedback['score'] * 10 }}%"></div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $feedback['summary'] }}</p>
                                    
                                    <div class="space-y-1">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-500">Suggestions for improvement:</p>
                                        @foreach($feedback['suggestions'] as $suggestion)
                                        <p class="text-xs text-gray-600 dark:text-gray-400">• {{ $suggestion }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Presentation Feedback -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Presentation Feedback</h4>
                        <div class="space-y-4">
                            @if(isset($currentQuestion['feedback']['presentation_feedback']))
                                @foreach($currentQuestion['feedback']['presentation_feedback'] as $feedback)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            @php
                                                $iconClass = $feedback['score'] >= 8 ? 'text-green-600' : ($feedback['score'] >= 6 ? 'text-yellow-600' : 'text-red-600');
                                                $icon = $feedback['score'] >= 8 ? '✓' : ($feedback['score'] >= 6 ? '!' : '✗');
                                            @endphp
                                            <span class="text-xl {{ $iconClass }}">{{ $icon }}</span>
                                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $feedback['category'] }}</h5>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $feedback['score'] }}/10</span>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-3">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $feedback['score'] * 10 }}%"></div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $feedback['summary'] }}</p>
                                    
                                    <div class="space-y-1">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-500">Suggestions for improvement:</p>
                                        @foreach($feedback['suggestions'] as $suggestion)
                                        <p class="text-xs text-gray-600 dark:text-gray-400">• {{ $suggestion }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>


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
    let mediaStream = null;
    let recordingInterval;
    
    Livewire.on('start-recording', function () {
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(function(stream) {
                mediaStream = stream;
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
                    // Clean up the stream
                    if (mediaStream) {
                        mediaStream.getTracks().forEach(track => track.stop());
                        mediaStream = null;
                    }
                    
                    // Clear the recording timer
                    if (recordingInterval) {
                        clearInterval(recordingInterval);
                        recordingInterval = null;
                    }
                    
                    // Create video blob for playback
                    const videoBlob = new Blob(recordedChunks, { type: 'video/webm' });
                    const videoUrl = URL.createObjectURL(videoBlob);
                    
                    // Store video URL for playback
                    window.lastRecordedVideoUrl = videoUrl;
                    
                    // Send recording data to Livewire
                    Livewire.dispatch('recording-completed', { 
                        chunks: recordedChunks,
                        videoUrl: videoUrl,
                        duration: recordingTime
                    });
                    
                    // Auto-submit after a brief delay to show completion
                    setTimeout(() => {
                        Livewire.dispatch('auto-submit-response');
                    }, 2000);
                };
                
                mediaRecorder.start(1000); // Record in 1-second chunks
                
                // Start recording timer
                let recordingTime = 0;
                recordingInterval = setInterval(function() {
                    recordingTime++;
                    Livewire.dispatch('recording-time-update', { time: recordingTime });
                }, 1000);
                
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
        
        // Clear the recording timer
        if (recordingInterval) {
            clearInterval(recordingInterval);
            recordingInterval = null;
        }
    });
    
    // Handle recording time updates
    Livewire.on('recording-time-update', function (data) {
        // This will be handled by the Livewire component
    });
});

// Global function to play the last recorded video
function playLastRecording() {
    const videoPlayer = document.getElementById('playback-video');
    const playButton = event.target;
    
    if (window.lastRecordedVideoUrl) {
        // Show the video player
        videoPlayer.style.display = 'block';
        videoPlayer.src = window.lastRecordedVideoUrl;
        
        // Update button text
        playButton.textContent = 'Playing...';
        playButton.disabled = true;
        
        // Handle video end
        videoPlayer.onended = function() {
            playButton.textContent = 'Play Back';
            playButton.disabled = false;
            videoPlayer.style.display = 'none';
        };
        
        // Handle video error
        videoPlayer.onerror = function() {
            playButton.textContent = 'Play Back';
            playButton.disabled = false;
            alert('Error playing video. Please try recording again.');
        };
        
        // Start playing
        videoPlayer.play();
    } else {
        alert('No recording available to play back.');
    }
}

// Function to play recordings from history
function playHistoryRecording(attemptId, videoUrl) {
    const videoPlayer = document.getElementById('history-video-' + attemptId);
    const playButton = event.target;
    
    if (videoUrl) {
        // Show the video player
        videoPlayer.style.display = 'block';
        videoPlayer.src = videoUrl;
        
        // Update button text
        const originalText = playButton.textContent;
        playButton.textContent = 'Playing...';
        playButton.disabled = true;
        
        // Handle video end
        videoPlayer.onended = function() {
            playButton.textContent = originalText;
            playButton.disabled = false;
            videoPlayer.style.display = 'none';
        };
        
        // Handle video error
        videoPlayer.onerror = function() {
            playButton.textContent = originalText;
            playButton.disabled = false;
            alert('Error playing video from history.');
        };
        
        // Start playing
        videoPlayer.play();
    } else {
        alert('No video available for this attempt.');
    }
}

// Video playback functions for recorded videos
function toggleVideoPlayback() {
    const video = document.getElementById('recorded-video');
    const overlay = document.getElementById('video-overlay');
    const playIcon = document.getElementById('play-icon');
    const pauseIcon = document.getElementById('pause-icon');
    
    if (video && video.paused) {
        video.play();
        if (overlay) overlay.style.display = 'none';
    } else if (video) {
        video.pause();
        if (overlay) {
            overlay.style.display = 'flex';
            if (playIcon) playIcon.classList.remove('hidden');
            if (pauseIcon) pauseIcon.classList.add('hidden');
        }
    }
}

function downloadVideo() {
    const video = document.getElementById('recorded-video');
    if (video && video.src) {
        const link = document.createElement('a');
        link.href = video.src;
        link.download = `interview-response-${new Date().toISOString().split('T')[0]}.webm`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

function shareVideo() {
    const video = document.getElementById('recorded-video');
    if (video && video.src) {
        if (navigator.share) {
            navigator.share({
                title: 'Interview Practice Response',
                text: 'Check out my interview practice response',
                url: video.src
            });
        } else {
            // Fallback: copy URL to clipboard
            navigator.clipboard.writeText(video.src).then(() => {
                alert('Video URL copied to clipboard!');
            });
        }
    }
}

// Initialize video event listeners
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('recorded-video');
    if (video) {
        const overlay = document.getElementById('video-overlay');
        const playIcon = document.getElementById('play-icon');
        const pauseIcon = document.getElementById('pause-icon');
        
        video.addEventListener('play', function() {
            if (overlay) overlay.style.display = 'none';
        });
        
        video.addEventListener('pause', function() {
            if (overlay) {
                overlay.style.display = 'flex';
                if (playIcon) playIcon.classList.remove('hidden');
                if (pauseIcon) pauseIcon.classList.add('hidden');
            }
        });
        
        video.addEventListener('ended', function() {
            if (overlay) {
                overlay.style.display = 'flex';
                if (playIcon) playIcon.classList.remove('hidden');
                if (pauseIcon) pauseIcon.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
