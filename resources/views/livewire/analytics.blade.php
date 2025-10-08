<div class="space-y-6">
    <!-- Overall Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Overall Mastery Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Overall Mastery</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['overallStats']['overall_mastery'], 1) }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Applications Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Applications</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $data['applicationStats']['total_applications'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Rate Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Success Rate</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['applicationStats']['success_rate'], 1) }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Score Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Average Score</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['overallStats']['average_score'], 1) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mastery Scores Section -->
    @if(count($data['masteryScores']) > 0)
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Mastery Scores</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Topic</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Skill</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attempts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Practiced</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($data['masteryScores'] as $score)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $score->topic }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $score->skill }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($score->score, 0) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        @php $scoreWidth = min(100, max(0, $score->score)); @endphp
                                        <div class="bg-gradient-to-r from-purple-500 to-blue-600 h-2 rounded-full transition-all duration-300"
                                             @style(['width' => $scoreWidth . '%'])></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $score->attempts }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $score->last_practiced_at ? $score->last_practiced_at->diffForHumans() : 'Never' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Topic Progress Section -->
    @if(count($data['topicProgress']) > 0)
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">Topic Progress</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($data['topicProgress'] as $progress)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $progress->topic }}</h4>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $progress->attempts_count }} attempts</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mb-2">
                    @php $progressWidth = min(100, max(0, $progress->calculateCompletionPercentage())); @endphp
                    <div class="bg-gradient-to-r from-green-500 to-teal-600 h-2 rounded-full transition-all duration-300"
                         @style(['width' => $progressWidth . '%'])></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>{{ number_format($progress->calculateCompletionPercentage(), 1) }}% complete</span>
                    <span>{{ number_format($progress->getAccuracyPercentage(), 1) }}% accuracy</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Application Stats Section -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">Application Statistics</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $data['applicationStats']['total_applications'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Applications</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $data['applicationStats']['successful_applications'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Successful</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ number_format($data['applicationStats']['success_rate'], 1) }}%</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Success Rate</div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    @if(count($data['masteryScores']) === 0 && count($data['topicProgress']) === 0)
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-12 text-center">
        <div class="p-4 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full w-16 h-16 mx-auto mb-4">
            <svg class="w-8 h-8 text-white mx-auto mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Analytics Data Yet</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Start practicing interviews to see your progress and analytics here.</p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:from-purple-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
            Start Practicing
        </a>
    </div>
    @endif
</div>