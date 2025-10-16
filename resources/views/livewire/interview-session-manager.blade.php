<div>
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(!$currentSession)
        {{-- Session Creation Form --}}
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
            <div class="p-8">
                <div class="max-w-2xl">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Configure Interview Session</h2>

                    <form wire:submit="startSession" class="space-y-6">
                        <!-- Interview Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Interview Type</label>
                            <select wire:model="session_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="behavioral">Behavioral</option>
                                <option value="technical">Technical</option>
                                <option value="case_study">Case Study</option>
                            </select>
                        </div>

                        <!-- Focus Area -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Focus Area</label>
                            <input type="text" wire:model="focus_area" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Difficulty -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Difficulty</label>
                            <select wire:model="difficulty" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>

                        <!-- Job Posting (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Posting (Optional)</label>
                            <select wire:model="job_posting_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Select a job posting</option>
                                @foreach($jobPostings as $posting)
                                    <option value="{{ $posting['id'] }}">{{ $posting['title'] }} - {{ $posting['company'] }}</option>
                                @endforeach
                            </select>
                        </div>


                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200">
                            Start Mock Interview
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        {{-- Active Interview Session --}}
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Mock Interview</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Question {{ $questionNumber }} of {{ $totalQuestions }}</p>
                    </div>
                    <button wire:click="endSession" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                        End Session
                    </button>
                </div>

                @if($currentQuestion)
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $currentQuestion['question'] ?? 'Question' }}</h4>

                        @if($isSubmitted && $feedback)
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                                <h5 class="font-medium text-green-800 dark:text-green-200 mb-2">Feedback:</h5>
                                <p class="text-green-700 dark:text-green-300">{{ $feedback }}</p>
                            </div>
                        @endif

                        @if(!$isSubmitted)
                            <div class="space-y-4">
                                <textarea
                                    wire:model="userAnswer"
                                    rows="6"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="Type your answer here..."
                                ></textarea>

                                <button
                                    wire:click="submitAnswer"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200"
                                    {{ empty($userAnswer) ? 'disabled' : '' }}
                                >
                                    Submit Answer
                                </button>
                            </div>
                        @else
                            <button
                                wire:click="loadNextQuestion"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200"
                            >
                                Next Question
                            </button>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600 dark:text-gray-400">Loading next question...</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Recent Sessions List --}}
    @if($sessions->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Sessions</h3>
            <div class="space-y-2">
                @foreach($sessions as $session)
                    <div class="bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ ucfirst($session->session_type) }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $session->aiPersona?->name ?? 'No AI Persona' }}</p>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $session->created_at->format('M d, Y') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
