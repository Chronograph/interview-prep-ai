<div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/30 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Interview Scoring</h3>
        </div>
        
        <div class="flex items-center gap-3">
            @if($isCalculating)
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 border-2 border-purple-600 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm text-purple-600 dark:text-purple-400">Calculating...</span>
                </div>
            @endif
            
            <button 
                wire:click="exportReport"
                class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export
            </button>
        </div>
    </div>

    <!-- Overall Score Display -->
    <div class="bg-gradient-to-r from-gray-50/80 to-purple-50/80 dark:from-gray-800/50 dark:to-purple-900/20 rounded-xl p-6 mb-6 border border-gray-200/50 dark:border-gray-700/50">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <div class="text-5xl font-bold" 
                         :class="{
                             'text-green-600 dark:text-green-400': gradeColor === 'green',
                             'text-blue-600 dark:text-blue-400': gradeColor === 'blue', 
                             'text-yellow-600 dark:text-yellow-400': gradeColor === 'yellow',
                             'text-orange-600 dark:text-orange-400': gradeColor === 'orange',
                             'text-red-600 dark:text-red-400': gradeColor === 'red'
                         }">
                        {{ $currentScore }}
                    </div>
                    <div class="flex flex-col">
                        <div class="px-4 py-2 rounded-full text-lg font-bold"
                             :class="{
                                 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200': gradeColor === 'green',
                                 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200': gradeColor === 'blue',
                                 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200': gradeColor === 'yellow', 
                                 'bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-200': gradeColor === 'orange',
                                 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200': gradeColor === 'red'
                             }">
                            Grade {{ $overallGrade }}
                        </div>
                        @if($improvementTrend != 0)
                            <div class="flex items-center gap-1 mt-2">
                                @if($improvementTrend > 0)
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                                    </svg>
                                    <span class="text-sm text-green-600 dark:text-green-400">+{{ $improvementTrend }} from last attempt</span>
                                @else
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                                    </svg>
                                    <span class="text-sm text-red-600 dark:text-red-400">{{ $improvementTrend }} from last attempt</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-2">
                    <div class="h-3 rounded-full transition-all duration-1000 ease-out"
                         :style="{
                             width: currentScore + '%',
                             background: gradeColor === 'green' ? 'linear-gradient(to right, #10b981, #059669)' :
                                        gradeColor === 'blue' ? 'linear-gradient(to right, #3b82f6, #2563eb)' :
                                        gradeColor === 'yellow' ? 'linear-gradient(to right, #f59e0b, #d97706)' :
                                        gradeColor === 'orange' ? 'linear-gradient(to right, #f97316, #ea580c)' :
                                        'linear-gradient(to right, #ef4444, #dc2626)'
                         }"></div>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Overall Interview Performance</div>
            </div>
            
            <!-- Score Ring -->
            <div class="relative w-24 h-24 ml-6">
                <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="2" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                    <path stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" 
                          :style="{
                              color: gradeColor === 'green' ? '#10b981' :
                                     gradeColor === 'blue' ? '#3b82f6' :
                                     gradeColor === 'yellow' ? '#f59e0b' :
                                     gradeColor === 'orange' ? '#f97316' : '#ef4444',
                              'stroke-dasharray': currentScore + ', 100'
                          }"
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $currentScore }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Breakdown -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Score Breakdown</h4>
            <button 
                wire:click="toggleDetailedView"
                class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium"
            >
                {{ $showDetailedView ? 'Hide Details' : 'Show Details' }}
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($scoreBreakdown as $key => $category)
                <div class="bg-gradient-to-r from-gray-50/80 dark:from-gray-800/50 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50 cursor-pointer hover:shadow-lg transition-all duration-200"
                     :class="{
                         'to-green-50/80 dark:to-green-900/20': category.color === 'green',
                         'to-blue-50/80 dark:to-blue-900/20': category.color === 'blue',
                         'to-yellow-50/80 dark:to-yellow-900/20': category.color === 'yellow',
                         'to-orange-50/80 dark:to-orange-900/20': category.color === 'orange',
                         'ring-2 ring-green-500': selectedCategory === '{{ $key }}' && category.color === 'green',
                         'ring-2 ring-blue-500': selectedCategory === '{{ $key }}' && category.color === 'blue',
                         'ring-2 ring-yellow-500': selectedCategory === '{{ $key }}' && category.color === 'yellow',
                         'ring-2 ring-orange-500': selectedCategory === '{{ $key }}' && category.color === 'orange'
                     }"
                     wire:click="selectCategory('{{ $key }}')"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 rounded-lg"
                             :style="{
                                 backgroundColor: category.color === 'green' ? '#dcfce7' :
                                                 category.color === 'blue' ? '#dbeafe' :
                                                 category.color === 'yellow' ? '#fef3c7' :
                                                 category.color === 'orange' ? '#fed7aa' : '#fecaca'
                             }">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 :style="{
                                     color: category.color === 'green' ? '#059669' :
                                           category.color === 'blue' ? '#2563eb' :
                                           category.color === 'yellow' ? '#d97706' :
                                           category.color === 'orange' ? '#ea580c' : '#dc2626'
                                 }">
                                @if($category['icon'] === 'academic-cap')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                @elseif($category['icon'] === 'chat-bubble-left-right')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                @elseif($category['icon'] === 'puzzle-piece')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                                @elseif($category['icon'] === 'presentation-chart-line')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                @elseif($category['icon'] === 'user-tie')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $category['name'] }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">{{ $category['score'] }}/{{ $category['max_score'] }} points</div>
                        </div>
                    </div>
                    
                    <!-- Category Progress -->
                    <div class="mb-2">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-1000 ease-out"
                                 :style="{
                                     width: category.percentage + '%',
                                     background: category.color === 'green' ? 'linear-gradient(to right, #10b981, #059669)' :
                                                category.color === 'blue' ? 'linear-gradient(to right, #3b82f6, #2563eb)' :
                                                category.color === 'yellow' ? 'linear-gradient(to right, #f59e0b, #d97706)' :
                                                category.color === 'orange' ? 'linear-gradient(to right, #f97316, #ea580c)' :
                                                'linear-gradient(to right, #ef4444, #dc2626)'
                                 }"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold"
                              :style="{
                                  color: category.color === 'green' ? '#059669' :
                                        category.color === 'blue' ? '#2563eb' :
                                        category.color === 'yellow' ? '#d97706' :
                                        category.color === 'orange' ? '#ea580c' : '#dc2626'
                              }">{{ $category['percentage'] }}%</span>
                        @if($category['percentage'] >= 80)
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">Excellent</span>
                        @elseif($category['percentage'] >= 70)
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-xs font-semibold rounded-full">Good</span>
                        @elseif($category['percentage'] >= 60)
                            <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200 text-xs font-semibold rounded-full">Fair</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 text-xs font-semibold rounded-full">Needs Work</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Analytics Dashboard -->
    @if($showDetailedView)
        <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Performance Analytics</h4>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Questions Answered -->
                <div class="bg-gradient-to-r from-gray-50/80 to-blue-50/80 dark:from-gray-800/50 dark:to-blue-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $analytics['questions_answered'] }}/{{ $analytics['total_questions'] }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Questions</div>
                        </div>
                    </div>
                </div>

                <!-- Average Response Time -->
                <div class="bg-gradient-to-r from-gray-50/80 to-green-50/80 dark:from-gray-800/50 dark:to-green-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ round($analytics['average_response_time']) }}s</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Avg Response</div>
                        </div>
                    </div>
                </div>

                <!-- Speaking Time -->
                <div class="bg-gradient-to-r from-gray-50/80 to-purple-50/80 dark:from-gray-800/50 dark:to-purple-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ gmdate("i:s", $analytics['total_speaking_time']) }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Speaking Time</div>
                        </div>
                    </div>
                </div>

                <!-- Confidence Level -->
                <div class="bg-gradient-to-r from-gray-50/80 to-orange-50/80 dark:from-gray-800/50 dark:to-orange-900/20 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $analytics['confidence_level'] }}%</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Confidence</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Feedback Section -->
    @if(!empty($strengths) || !empty($improvements))
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Strengths -->
            @if(!empty($strengths))
                <div class="bg-gradient-to-r from-green-50/80 to-emerald-50/80 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200/50 dark:border-green-700/50">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h5 class="font-semibold text-green-800 dark:text-green-200">Strengths</h5>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($strengths as $strength)
                            <div class="flex items-start gap-3 p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg border border-white/30 dark:border-gray-700/30">
                                <div class="p-1 bg-green-100 dark:bg-green-900/50 rounded">
                                    <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-green-800 dark:text-green-200">{{ $strength['category'] }}</div>
                                    <p class="text-sm text-green-700 dark:text-green-300">{{ $strength['message'] }}</p>
                                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">{{ round($strength['score']) }}% performance</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Areas for Improvement -->
            @if(!empty($improvements))
                <div class="bg-gradient-to-r from-orange-50/80 to-red-50/80 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-4 border border-orange-200/50 dark:border-orange-700/50">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h5 class="font-semibold text-orange-800 dark:text-orange-200">Areas for Improvement</h5>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($improvements as $improvement)
                            <div class="flex items-start gap-3 p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg border border-white/30 dark:border-gray-700/30">
                                <div class="p-1 bg-orange-100 dark:bg-orange-900/50 rounded">
                                    <svg class="w-3 h-3 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-orange-800 dark:text-orange-200">{{ $improvement['category'] }}</div>
                                    <p class="text-sm text-orange-700 dark:text-orange-300">{{ $improvement['message'] }}</p>
                                    <div class="text-xs text-orange-600 dark:text-orange-400 mt-1">{{ round($improvement['score']) }}% performance</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Recommendations -->
    @if(!empty($recommendations))
        <div class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-blue-200/50 dark:border-blue-700/50">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                <h5 class="font-semibold text-blue-800 dark:text-blue-200">Recommendations</h5>
            </div>
            
            <div class="space-y-3">
                @foreach($recommendations as $recommendation)
                    <div class="flex items-start gap-3 p-3 bg-white/50 dark:bg-gray-800/50 rounded-lg border border-white/30 dark:border-gray-700/30">
                        <div class="p-1 rounded"
                                     :class="{
                                         'bg-red-100 dark:bg-red-900/50': recommendation.priority === 'high',
                                         'bg-yellow-100 dark:bg-yellow-900/50': recommendation.priority === 'medium',
                                         'bg-blue-100 dark:bg-blue-900/50': recommendation.priority === 'low'
                                     }">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 :class="{
                                     'text-red-600 dark:text-red-400': recommendation.priority === 'high',
                                     'text-yellow-600 dark:text-yellow-400': recommendation.priority === 'medium',
                                     'text-blue-600 dark:text-blue-400': recommendation.priority === 'low'
                                 }">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-blue-800 dark:text-blue-200 capitalize">{{ $recommendation['type'] }}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
                                      :class="{
                                          'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200': recommendation.priority === 'high',
                                          'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200': recommendation.priority === 'medium',
                                          'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200': recommendation.priority === 'low'
                                      }">{{ $recommendation['priority'] }}</span>
                            </div>
                            <p class="text-sm text-blue-700 dark:text-blue-300">{{ $recommendation['message'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Export Modal -->
    @if($showExportModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50" wire:click="closeExportModal">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Export Report</h3>
                    <button wire:click="closeExportModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-3">
                    <button 
                        wire:click="downloadPDF"
                        class="w-full flex items-center gap-3 p-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold">Download PDF Report</div>
                            <div class="text-sm opacity-90">Complete interview analysis</div>
                        </div>
                    </button>
                    
                    <button 
                        wire:click="shareResults"
                        class="w-full flex items-center gap-3 p-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold">Share Results</div>
                            <div class="text-sm opacity-90">Share your score and progress</div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>