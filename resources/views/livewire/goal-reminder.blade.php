@if($this->goalReminders)
    <div class="space-y-3">
        @foreach($this->goalReminders as $reminder)
            @if($compact)
                <!-- Compact version for smaller spaces -->
                <div class="flex items-center justify-between p-3 bg-{{ $reminder['color'] }}-50 rounded-lg border border-{{ $reminder['color'] }}-200">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-{{ $reminder['color'] }}-100 rounded-lg flex items-center justify-center">
                            <x-icon name="{{ $reminder['icon'] }}" class="w-3 h-3 text-{{ $reminder['color'] }}-600" />
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $reminder['message'] }}</span>
                    </div>
                    <x-button
                        size="xs"
                        color="{{ $reminder['color'] }}"
                        wire:click="$dispatch('redirect', { url: '{{ $reminder['action_url'] }}' })"
                    >
                        {{ $reminder['action'] }}
                    </x-button>
                </div>
            @else
                <!-- Full version -->
                <div class="flex items-center justify-between p-4 bg-{{ $reminder['color'] }}-50 rounded-lg border border-{{ $reminder['color'] }}-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-{{ $reminder['color'] }}-100 rounded-lg flex items-center justify-center">
                            <x-icon name="{{ $reminder['icon'] }}" class="w-4 h-4 text-{{ $reminder['color'] }}-600" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $reminder['message'] }}</p>
                            <div class="w-32 bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-{{ $reminder['color'] }}-600 h-2 rounded-full" style="width: {{ $reminder['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <x-button
                        size="sm"
                        color="{{ $reminder['color'] }}"
                        wire:click="$dispatch('redirect', { url: '{{ $reminder['action_url'] }}' })"
                    >
                        {{ $reminder['action'] }}
                    </x-button>
                </div>
            @endif
        @endforeach
    </div>
@endif
