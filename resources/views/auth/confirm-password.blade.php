<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Confirm Password</h1>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            This is a secure area of the application. Please confirm your password before continuing.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf
        
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-800 dark:text-red-200">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Password Field -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Current Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" type="password" name="password" placeholder="Enter your current password" required autofocus autocomplete="current-password" />
            </div>
        </div>

        <!-- Confirm Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 px-4 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 focus:ring-4 focus:ring-orange-300 dark:focus:ring-orange-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Confirm Password') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Security Notice -->
    <div class="mt-8 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div class="text-sm">
                <p class="text-amber-800 dark:text-amber-200 font-medium mb-1">Security Check</p>
                <p class="text-amber-700 dark:text-amber-300">
                    We need to verify your identity before accessing this secure area. Your password will not be stored.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>