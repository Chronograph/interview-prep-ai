<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Start Mock Interview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Configure Interview</h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Set up your practice session with AI-powered interview simulation</p>
                    </div>
                </div>
            </div>

            <!-- Main Configuration Card -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="p-8">
                    <form id="interview-form" class="space-y-8">
                        @csrf

                        <!-- Interview Type Section -->
                        <div class="space-y-6">
                            <label class="block text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Interview Type</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <label class="flex items-center p-6 bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl border-2 border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-300 hover:shadow-xl transition-all duration-300 cursor-pointer">
                                    <input type="radio" name="session_type" value="behavioral" checked class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <div class="flex items-start space-x-4 ml-4">
                                        <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Behavioral</h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm">Situational and experience-based questions</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="flex items-center p-6 bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl border-2 border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-300 hover:shadow-xl transition-all duration-300 cursor-pointer">
                                    <input type="radio" name="session_type" value="technical" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="flex items-start space-x-4 ml-4">
                                        <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Technical</h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm">Skills and knowledge-based questions</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Focus Area -->
                        <div class="space-y-4">
                            <label class="block text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Focus Area</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="focus_area"
                                    value="General Interview Skills"
                                    class="w-full px-4 py-4 bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm border border-gray-200 dark:border-gray-600 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-300"
                                    placeholder="e.g., Leadership, Problem Solving, Communication"
                                />
                            </div>
                        </div>

                        <!-- Difficulty Level -->
                        <div class="space-y-4">
                            <label class="block text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Difficulty Level</label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="flex items-center flex-col p-4 bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-300 hover:shadow-xl transition-all duration-300 cursor-pointer">
                                    <input type="radio" name="difficulty" value="beginner" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 mb-2">
                                    <div class="text-green-600 dark:text-green-400 font-bold">Beginner</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Entry level</div>
                                </label>

                                <label class="flex items-center flex-col p-4 bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-600 hover:border-yellow-300 dark:hover:border-yellow-300 hover:shadow-xl transition-all duration-300 cursor-pointer">
                                    <input type="radio" name="difficulty" value="intermediate" checked class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 mb-2">
                                    <div class="text-yellow-600 dark:text-yellow-400 font-bold">Intermediate</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mid level</div>
                                </label>

                                <label class="flex items-center flex-col p-4 bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-600 hover:border-red-300 dark:hover:border-red-300 hover:shadow-xl transition-all duration-300 cursor-pointer">
                                    <input type="radio" name="difficulty" value="advanced" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 mb-2">
                                    <div class="text-red-600 dark:text-red-400 font-bold">Advanced</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Senior level</div>
                                </label>
                            </div>
                        </div>

                        <!-- Job Posting Selection -->
                        @if(count($jobPostings) > 0)
                        <div class="space-y-4">
                            <label class="block text-lg font-bold text-gray-900 dark:text-gray-100">Job Posting (Optional)</label>
                            <select
                                name="job_posting_id"
                                class="w-full px-4 py-3 bg-white/60 dark:bg-gray-700/60 backdrop-blur-sm border border-gray-200 dark:border-gray-600 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                            >
                                <option value="">Select a job posting (optional)</option>
                                @foreach($jobPostings as $jobPosting)
                                    <option value="{{ $jobPosting->id }}">{{ $jobPosting->title }} - {{ $jobPosting->company }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                    </form>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8">
                <a
                    href="{{ route('practice.mock-interviews') }}"
                    class="inline-flex items-center px-6 py-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl font-semibold text-sm text-gray-700 dark:text-gray-300 shadow-lg hover:bg-white/90 dark:hover:bg-gray-700/90 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ← Back to Practice
                </a>

                <button
                    type="submit"
                    form="interview-form"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-2xl font-semibold text-white shadow-xl hover:from-purple-700 hover:to-blue-700 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 110 5H9V10z"></path>
                    </svg>
                    Start Mock Interview
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('interview-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('{{ route("interview-sessions.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    alert('Error: ' + (result.message || 'Failed to start interview session'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to start interview session. Please try again.');
            }
        });
    </script>
</x-app-layout>
