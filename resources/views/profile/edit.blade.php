<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    {{ __('Profile Settings') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Manage your account information and preferences
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="p-8">
                    <section>
                        <header class="flex items-center space-x-4 mb-8">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                    {{ __('Profile Information') }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Update your account's profile information and email address.") }}
                                </p>
                            </div>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="name" :value="__('Full Name')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="name" name="name" type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('name', $user->name)" placeholder="Enter your full name" required autofocus autocomplete="name" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="email" :value="__('Email Address')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="email" name="email" type="email" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('email', $user->email)" placeholder="Enter your email address" required autocomplete="username" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="mt-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                                        {{ __('Your email address is unverified.') }}
                                                    </p>
                                                    <button form="send-verification" class="mt-1 text-sm text-amber-700 dark:text-amber-300 hover:text-amber-900 dark:hover:text-amber-100 underline font-medium transition duration-200">
                                                        {{ __('Click here to re-send the verification email.') }}
                                                    </button>
                                                </div>
                                            </div>
                                            @if (session('status') === 'verification-link-sent')
                                                <div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                                        {{ __('A new verification link has been sent to your email address.') }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-6">
                                <div class="flex items-center space-x-4">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>

                                @if (session('status') === 'profile-updated')
                                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center space-x-2 px-4 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ __('Profile updated successfully!') }}</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="p-8">
                    <section>
                        <header class="flex items-center space-x-4 mb-8">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                    {{ __('Update Password') }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                </p>
                            </div>
                        </header>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div class="space-y-2">
                                <x-input-label for="current_password" :value="__('Current Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <x-text-input id="current_password" name="current_password" type="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Enter current password" autocomplete="current-password" />
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="password" :value="__('New Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                    <x-text-input id="password" name="password" type="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Enter new password" autocomplete="new-password" />
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Confirm new password" autocomplete="new-password" />
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between pt-6">
                                <div class="flex items-center space-x-4">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ __('Update Password') }}
                                    </button>
                                </div>

                                @if (session('status') === 'password-updated')
                                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center space-x-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ __('Password updated successfully!') }}</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="p-8">
                    <section class="space-y-6">
                        <header class="flex items-center space-x-4 mb-8">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">
                                    {{ __('Delete Account') }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                                </p>
                            </div>
                        </header>

                        <button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('Delete Account') }}
                        </button>

                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                             <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
                                 @csrf
                                 @method('delete')

                                 <div class="flex items-center space-x-4 mb-6">
                                     <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg">
                                         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                         </svg>
                                     </div>
                                     <div>
                                         <h2 class="text-xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">
                                             {{ __('Are you sure you want to delete your account?') }}
                                         </h2>
                                     </div>
                                 </div>

                                 <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                                     <p class="text-sm text-red-800 dark:text-red-200">
                                         {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                                     </p>
                                 </div>

                                 <div class="space-y-2">
                                     <x-input-label for="password" value="{{ __('Password') }}" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                     <div class="relative">
                                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                             <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                             </svg>
                                         </div>
                                         <x-text-input
                                             id="password"
                                             name="password"
                                             type="password"
                                             class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                             placeholder="{{ __('Enter your password to confirm') }}"
                                         />
                                     </div>
                                     <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                 </div>

                                 <div class="flex items-center justify-end space-x-4 mt-8">
                                     <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                                         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                         </svg>
                                         {{ __('Cancel') }}
                                     </button>

                                     <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800">
                                         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                         </svg>
                                         {{ __('Delete Account') }}
                                     </button>
                                 </div>
                             </form>
                         </x-modal>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
</x-app-layout>