<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $cheatSheet->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Category</div>
                            <div class="text-lg font-semibold text-blue-900 dark:text-blue-100">{{ $cheatSheet->getCategoryDisplayAttribute() }}</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <div class="text-sm font-medium text-green-600 dark:text-green-400">Usage Count</div>
                            <div class="text-lg font-semibold text-green-900 dark:text-green-100">{{ $cheatSheet->usage_count }} times</div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                            <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Average Score</div>
                            <div class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">
                                {{ $cheatSheet->average_score ? number_format($cheatSheet->average_score, 1) . '%' : 'Not practiced' }}
                            </div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                            <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Last Practiced</div>
                            <div class="text-lg font-semibold text-purple-900 dark:text-purple-100">
                                {{ $cheatSheet->last_practiced_at ? $cheatSheet->last_practiced_at->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                    </div>

                    <!-- Key Points -->
                    @if($cheatSheet->key_points)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Key Points</h3>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                                @if(is_array($cheatSheet->key_points))
                                    <ul class="list-disc list-inside space-y-3">
                                        @foreach($cheatSheet->key_points as $point)
                                            <li class="text-gray-700 dark:text-gray-300">{{ $point }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-700 dark:text-gray-300">{{ $cheatSheet->key_points }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Suggested Response -->
                    @if($cheatSheet->suggested_response)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Suggested Response Framework</h3>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $cheatSheet->suggested_response }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Examples -->
                    @if($cheatSheet->examples)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Examples</h3>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-6">
                                @if(is_array($cheatSheet->examples))
                                    @foreach($cheatSheet->examples as $example)
                                        <div class="mb-4 last:mb-0 p-4 bg-white dark:bg-gray-800 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                            <p class="text-gray-700 dark:text-gray-300">{{ $example }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $cheatSheet->examples }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Do's and Don'ts -->
                    @if($cheatSheet->do_say || $cheatSheet->dont_say)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Do's and Don'ts</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($cheatSheet->do_say)
                                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                                        <h4 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">Do Say</h4>
                                        @if(is_array($cheatSheet->do_say))
                                            <ul class="list-disc list-inside space-y-2">
                                                @foreach($cheatSheet->do_say as $do)
                                                    <li class="text-green-700 dark:text-green-300">{{ $do }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-green-700 dark:text-green-300">{{ $cheatSheet->do_say }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if($cheatSheet->dont_say)
                                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-6">
                                        <h4 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-3">Don't Say</h4>
                                        @if(is_array($cheatSheet->dont_say))
                                            <ul class="list-disc list-inside space-y-2">
                                                @foreach($cheatSheet->dont_say as $dont)
                                                    <li class="text-red-700 dark:text-red-300">{{ $dont }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-red-700 dark:text-red-300">{{ $cheatSheet->dont_say }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Follow-up Questions -->
                    @if($cheatSheet->follow_up_questions)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Follow-up Questions</h3>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6">
                                @if(is_array($cheatSheet->follow_up_questions))
                                    <ul class="list-disc list-inside space-y-3">
                                        @foreach($cheatSheet->follow_up_questions as $question)
                                            <li class="text-gray-700 dark:text-gray-300">{{ $question }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-700 dark:text-gray-300">{{ $cheatSheet->follow_up_questions }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex space-x-4">
                            <a href="{{ route('cheat-sheets.edit', $cheatSheet) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Edit') }}
                            </a>
                            <button onclick="refreshCheatSheet({{ $cheatSheet->id }})" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Refresh') }}
                            </button>
                        </div>
                        
                        <form method="POST" action="{{ route('cheat-sheets.destroy', $cheatSheet) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this cheat sheet?')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function refreshCheatSheet(cheatSheetId) {
            if (confirm('Are you sure you want to refresh this cheat sheet? This will regenerate the content.')) {
                fetch(`/cheat-sheets/${cheatSheetId}/refresh`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to refresh cheat sheet: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while refreshing the cheat sheet.');
                });
            }
        }
    </script>
</x-app-layout>