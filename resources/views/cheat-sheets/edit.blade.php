<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Cheat Sheet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('cheat-sheets.update', $cheatSheet) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $cheatSheet->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="behavioral" {{ $cheatSheet->category === 'behavioral' ? 'selected' : '' }}>Behavioral Questions</option>
                                <option value="technical" {{ $cheatSheet->category === 'technical' ? 'selected' : '' }}>Technical Questions</option>
                                <option value="company_specific" {{ $cheatSheet->category === 'company_specific' ? 'selected' : '' }}>Company-Specific</option>
                                <option value="general" {{ $cheatSheet->category === 'general' ? 'selected' : '' }}>General Interview</option>
                                <option value="custom" {{ $cheatSheet->category === 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="key_points" :value="__('Key Points')" />
                            <div id="key-points-container">
                                @if(is_array($cheatSheet->key_points))
                                    @foreach($cheatSheet->key_points as $index => $point)
                                        <div class="flex mb-2">
                                            <input type="text" name="key_points[]" value="{{ $point }}" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter a key point...">
                                            <button type="button" onclick="removeKeyPoint(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex mb-2">
                                        <input type="text" name="key_points[]" value="{{ $cheatSheet->key_points }}" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter a key point...">
                                        <button type="button" onclick="removeKeyPoint(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addKeyPoint()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Key Point</button>
                            <x-input-error :messages="$errors->get('key_points')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="suggested_response" :value="__('Suggested Response Framework')" />
                            <textarea id="suggested_response" name="suggested_response" rows="6" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter the suggested response framework...">{{ old('suggested_response', $cheatSheet->suggested_response) }}</textarea>
                            <x-input-error :messages="$errors->get('suggested_response')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="examples" :value="__('Examples')" />
                            <div id="examples-container">
                                @if(is_array($cheatSheet->examples))
                                    @foreach($cheatSheet->examples as $index => $example)
                                        <div class="flex mb-2">
                                            <textarea name="examples[]" rows="2" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter an example...">{{ $example }}</textarea>
                                            <button type="button" onclick="removeExample(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex mb-2">
                                        <textarea name="examples[]" rows="2" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter an example...">{{ $cheatSheet->examples }}</textarea>
                                        <button type="button" onclick="removeExample(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addExample()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Example</button>
                            <x-input-error :messages="$errors->get('examples')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="follow_up_questions" :value="__('Follow-up Questions')" />
                            <div id="follow-up-questions-container">
                                @if(is_array($cheatSheet->follow_up_questions))
                                    @foreach($cheatSheet->follow_up_questions as $index => $question)
                                        <div class="flex mb-2">
                                            <input type="text" name="follow_up_questions[]" value="{{ $question }}" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter a follow-up question...">
                                            <button type="button" onclick="removeFollowUpQuestion(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex mb-2">
                                        <input type="text" name="follow_up_questions[]" value="{{ $cheatSheet->follow_up_questions }}" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter a follow-up question...">
                                        <button type="button" onclick="removeFollowUpQuestion(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addFollowUpQuestion()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Follow-up Question</button>
                            <x-input-error :messages="$errors->get('follow_up_questions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('cheat-sheets.show', $cheatSheet) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Cheat Sheet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addKeyPoint() {
            const container = document.getElementById('key-points-container');
            const div = document.createElement('div');
            div.className = 'flex mb-2';
            div.innerHTML = `
                <input type="text" name="key_points[]" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter a key point...">
                <button type="button" onclick="removeKeyPoint(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
            `;
            container.appendChild(div);
        }

        function removeKeyPoint(button) {
            button.parentElement.remove();
        }

        function addExample() {
            const container = document.getElementById('examples-container');
            const div = document.createElement('div');
            div.className = 'flex mb-2';
            div.innerHTML = `
                <textarea name="examples[]" rows="2" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter an example..."></textarea>
                <button type="button" onclick="removeExample(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
            `;
            container.appendChild(div);
        }

        function removeExample(button) {
            button.parentElement.remove();
        }

        function addFollowUpQuestion() {
            const container = document.getElementById('follow-up-questions-container');
            const div = document.createElement('div');
            div.className = 'flex mb-2';
            div.innerHTML = `
                <input type="text" name="follow_up_questions[]" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter a follow-up question...">
                <button type="button" onclick="removeFollowUpQuestion(this)" class="ml-2 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
            `;
            container.appendChild(div);
        }

        function removeFollowUpQuestion(button) {
            button.parentElement.remove();
        }
    </script>
</x-app-layout>