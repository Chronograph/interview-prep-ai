<div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/30 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Real-Time Feedback</h3>
        </div>

        @if($isRecording)
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                <span class="text-sm font-medium text-red-600 dark:text-red-400">Live Analysis</span>
            </div>
        @endif
    </div>

    @if($isRecording || !empty($feedbackHistory))
        <!-- Current Score -->
        <div class="bg-gradient-to-r from-gray-50/80 to-blue-50/80 dark:from-gray-800/50 dark:to-blue-900/20 rounded-xl p-4 mb-6 border border-gray-200/50 dark:border-gray-700/50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Current Score</div>
                    <div class="flex items-center gap-3">
                        <div class="text-3xl font-bold text-{{ $scoreGrade['color'] }}-600 dark:text-{{ $scoreGrade['color'] }}-400">
                            {{ $currentScore }}
                        </div>
                        <div class="px-3 py-1 bg-{{ $scoreGrade['color'] }}-100 dark:bg-{{ $scoreGrade['color'] }}-900/50 text-{{ $scoreGrade['color'] }}-800 dark:text-{{ $scoreGrade['color'] }}-200 rounded-full text-sm font-semibold">
                            Grade {{ $scoreGrade['grade'] }}
                        </div>
                    </div>
                </div>

                <!-- Score Ring -->
                <div class="relative w-16 h-16">
                    <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="text-{{ $scoreGrade['color'] }}-500" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="{{ $currentScore }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $currentScore }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Metrics -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Speaking Pace -->
            <div class="bg-gradient-to-r from-gray-50/80 to-green-50/80 dark:from-gray-800/50 dark:to-green-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-{{ $paceIndicator['color'] }}-100 dark:bg-{{ $paceIndicator['color'] }}-900/50 rounded-lg">
                        <svg class="w-4 h-4 text-{{ $paceIndicator['color'] }}-600 dark:text-{{ $paceIndicator['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($paceIndicator['icon'] === 'check')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @elseif($paceIndicator['icon'] === 'chevron-up')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            @elseif($paceIndicator['icon'] === 'chevron-down')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            @elseif($paceIndicator['icon'] === 'minus')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            @elseif($paceIndicator['icon'] === 'plus')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Speaking Pace</div>
                        <div class="text-sm font-semibold text-{{ $paceIndicator['color'] }}-600 dark:text-{{ $paceIndicator['color'] }}-400">{{ $paceIndicator['text'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Audio Quality -->
            <div class="bg-gradient-to-r from-gray-50/80 to-blue-50/80 dark:from-gray-800/50 dark:to-blue-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-{{ $audioQualityIndicator['color'] }}-100 dark:bg-{{ $audioQualityIndicator['color'] }}-900/50 rounded-lg">
                        <div class="flex items-end gap-1">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="w-1 bg-{{ $i <= $audioQualityIndicator['bars'] ? $audioQualityIndicator['color'] . '-600' : 'gray-300' }} dark:bg-{{ $i <= $audioQualityIndicator['bars'] ? $audioQualityIndicator['color'] . '-400' : 'gray-600' }} rounded-full" style="height: {{ $i * 3 + 2 }}px;"></div>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Audio Quality</div>
                        <div class="text-sm font-semibold text-{{ $audioQualityIndicator['color'] }}-600 dark:text-{{ $audioQualityIndicator['color'] }}-400">{{ $audioQualityIndicator['text'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Filler Words -->
            <div class="bg-gradient-to-r from-gray-50/80 to-orange-50/80 dark:from-gray-800/50 dark:to-orange-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                        <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Filler Words</div>
                        <div class="text-sm font-semibold {{ $realTimeFeedback['filler_words'] > 3 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                            {{ $realTimeFeedback['filler_words'] }} detected
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eye Contact -->
            <div class="bg-gradient-to-r from-gray-50/80 to-purple-50/80 dark:from-gray-800/50 dark:to-purple-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Eye Contact</div>
                        <div class="text-sm font-semibold text-purple-600 dark:text-purple-400 capitalize">{{ $realTimeFeedback['eye_contact'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-Time Suggestions -->
        @if(!empty($suggestions))
            <div class="bg-gradient-to-r from-yellow-50/80 to-orange-50/80 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl p-4 mb-6 border border-yellow-200/50 dark:border-yellow-700/50">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">Live Suggestions</span>
                </div>

                <div class="space-y-2">
                    @foreach($suggestions as $suggestion)
                        <div class="flex items-start gap-3 p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg border border-white/30 dark:border-gray-700/30">
                            <div class="p-1 bg-{{ $suggestion['priority'] === 'high' ? 'red' : ($suggestion['priority'] === 'medium' ? 'yellow' : 'blue') }}-100 dark:bg-{{ $suggestion['priority'] === 'high' ? 'red' : ($suggestion['priority'] === 'medium' ? 'yellow' : 'blue') }}-900/50 rounded">
                                <svg class="w-3 h-3 text-{{ $suggestion['priority'] === 'high' ? 'red' : ($suggestion['priority'] === 'medium' ? 'yellow' : 'blue') }}-600 dark:text-{{ $suggestion['priority'] === 'high' ? 'red' : ($suggestion['priority'] === 'medium' ? 'yellow' : 'blue') }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($suggestion['icon'] === 'clock')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @elseif($suggestion['icon'] === 'microphone')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    @elseif($suggestion['icon'] === 'volume-up')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M9 12a1 1 0 01-1-1V9a1 1 0 011-1h1l3.5-3.5A1 1 0 0115 5.5v13a1 1 0 01-1.5.866L10 16H9a1 1 0 01-1-1v-2a1 1 0 011-1z"></path>
                                    @elseif($suggestion['icon'] === 'eye')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $suggestion['message'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="flex gap-3">
            @if($isAnalyzing)
                <x-button wire:click="pauseAnalysis" class="flex-1" warning icon="pause">
                    Pause Analysis
                </x-button>
            @elseif($isRecording && !$isAnalyzing)
                <x-button wire:click="resumeAnalysis" class="flex-1" success icon="play">
                    Resume Analysis
                </x-button>
            @endif

            <x-button wire:click="clearFeedback" secondary icon="trash">
                Clear Feedback
            </x-button>
        </div>
    @else
        <!-- Waiting State -->
        <div class="text-center py-8">
            <div class="p-4 bg-gradient-to-br from-gray-100 to-blue-100 dark:from-gray-800 dark:to-blue-900/50 rounded-2xl inline-block mb-4">
                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Ready for Analysis</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">Start recording to receive real-time feedback on your interview performance.</p>
        </div>
    @endif
</div>
