<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Verify Your Email</h1>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed max-w-md mx-auto">
            Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
        </p>
    </div>

    <!-- Success Message -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium text-green-800 dark:text-green-200">
                    {{ __('A new verification link has been sent to your email address.') }}
                </span>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button type="submit" class="w-full" secondary icon="arrow-right-on-rectangle">
                {{ __('Log Out') }}
            </x-button>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div class="text-sm">
                <p class="text-yellow-800 dark:text-yellow-200 font-medium mb-1">Didn't receive the email?</p>
                <p class="text-yellow-700 dark:text-yellow-300">
                    Check your spam folder or click the resend button above. If you continue having issues, please contact support.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
