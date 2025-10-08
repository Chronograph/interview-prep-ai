<div class="bg-white shadow rounded-lg p-6">
    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">AI Personas</h2>
        <div class="flex space-x-2">
            <x-button wire:click="openStatsModal" primary icon="chart-bar">
                {{ __('Statistics') }}
            </x-button>
            <x-button wire:click="openRecommendationsModal" success icon="light-bulb">
                {{ __('Recommendations') }}
            </x-button>
            <x-button wire:click="openCreateModal" primary icon="plus">
                {{ __('Add New Persona') }}
            </x-button>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex space-x-4 mb-6">
        <input wire:model.debounce.300ms="search" type="text" placeholder="Search personas..."
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

        <select wire:model.debounce.300ms="type" class="shadow border rounded w-full py-2 px-3 text-gray-700">
            <option value="">All Types</option>
            @foreach($personaTypes as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>

        <label class="flex items-center space-x-2">
            <input type="checkbox" wire:model.debounce.300ms="active_only">
            <span>{{ __('Active Only') }}</span>
        </label>
    </div>

    <!-- Personas List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($personas as $persona)
            <div class="border rounded-lg p-4 shadow">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold">{{ $persona->name }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $persona->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $persona->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <p class="text-gray-600 mb-3">{{ Str::limit($persona->description, 100) }}</p>

                <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                    <span>Type: {{ ucfirst($persona->persona_type) }}</span>
                    <span>Level: {{ ucfirst($persona->difficulty_level) }}</span>
                </div>

                <div class="flex flex-wrap gap-2">
                    <x-button wire:click="openEditModal({{ $persona->id }})" size="xs" primary icon="pencil">
                        Edit
                    </x-button>

                    <x-button wire:click="toggleActive({{ $persona->id }})" size="xs" warning>
                        {{ $persona->is_active ? 'Deactivate' : 'Activate' }}
                    </x-button>

                    <x-button wire:click="openTestModal({{ $persona->id }})" size="xs" icon="play" class="bg-purple-500 hover:bg-purple-600 text-white">
                        Test
                    </x-button>

                    <x-button wire:click="openCloneModal({{ $persona->id }})" size="xs" secondary icon="document-duplicate">
                        Clone
                    </x-button>

                    <x-button wire:click="openDeleteModal({{ $persona->id }})" size="xs" negative icon="trash">
                        Delete
                    </x-button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">{{ __('No AI personas found.') }}</p>
                <x-button wire:click="openCreateModal" primary icon="plus" class="mt-4">
                    {{ __('Create First Persona') }}
                </x-button>
            </div>
        @endforelse
    </div>

    <!-- Modal: Create Persona -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Create New AI Persona') }}</h3>

            <form wire:submit.prevent="createPersona">
                <div class="grid grid-cols-1 gap-4 my-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                        <input wire:model="name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                        <textarea wire:model="description" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        @error('description') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Type') }} {{ __('Type') }}</label>
                            <select wire:model="persona_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($personaTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Difficulty') }}</label>
                            <select wire:model="difficulty_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($difficultyLevels as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Interview Style') }}</label>
                        <select wire:model="interview_style" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($interviewStyles as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('System Prompt') }}</label>
                        <textarea wire:model="system_prompt" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <!-- Dynamic fields for traits and questions would be added here -->
                </div>

                <div class="flex justify-end space-x-2">
                    <x-button type="button" wire:click="closeCreateModal" secondary>
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button type="submit" primary>
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Other modals for edit, delete, stats, etc. would be added in similar structure -->
</div>
