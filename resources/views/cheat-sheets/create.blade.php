<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Cheat Sheet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('cheat-sheets.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="behavioral">Behavioral Questions</option>
                                <option value="technical">Technical Questions</option>
                                <option value="company_specific">Company-Specific</option>
                                <option value="general">General Interview</option>
                                <option value="custom">Custom</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="general">General</option>
                                <option value="personalized">Personalized</option>
                                <option value="job_specific">Job-Specific</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="job_posting_id" :value="__('Job Posting (Optional)')" />
                            <select id="job_posting_id" name="job_posting_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a job posting...</option>
                                @foreach($jobPostings as $jobPosting)
                                    <option value="{{ $jobPosting->id }}">{{ $jobPosting->title }} at {{ $jobPosting->company_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('job_posting_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="context" :value="__('Additional Context (Optional)')" />
                            <textarea id="context" name="context" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Any specific requirements or context for this cheat sheet...">{{ old('context') }}</textarea>
                            <x-input-error :messages="$errors->get('context')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('cheat-sheets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Generate Cheat Sheet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>