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
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Profile Overview Card -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center space-x-6">
                        <!-- Profile Photo -->
                        <div class="relative">
                            @if($user->profile_photo_path)
                                <img class="w-24 h-24 rounded-full object-cover ring-4 ring-white dark:ring-gray-800 shadow-lg"
                                     src="{{ Storage::url($user->profile_photo_path) }}"
                                     alt="{{ $user->name }}">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 shadow-lg">
                                    <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-2 border-white dark:border-gray-800"></div>
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            @if($user->bio)
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">{{ $user->bio }}</p>
                            @endif

                            <!-- Profile Completion -->
                            <div class="mt-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Completion</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->profile_completion ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    @php $completion = $user->profile_completion ?? 0; @endphp
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                         style="width: {{ $completion }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $user->interviewSessions()->count() }}</div>
                                <div class="text-xs text-blue-600 dark:text-blue-400">Sessions</div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $user->userDocuments()->count() }}</div>
                                <div class="text-xs text-green-600 dark:text-green-400">Documents</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Settings -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Personal Information -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-8">
                            <header class="flex items-center space-x-4 mb-8">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                        {{ __('Personal Information') }}
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __("Update your personal details and contact information.") }}
                                    </p>
                                </div>
                            </header>

                            <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <!-- Profile Photo Upload -->
                                <div class="space-y-4">
                                    <x-input-label for="profile_photo" :value="__('Profile Photo')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="flex items-center space-x-6">
                                        @if($user->profile_photo_path)
                                            <img class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-600"
                                                 src="{{ Storage::url($user->profile_photo_path) }}"
                                                 alt="{{ $user->name }}">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-gray-300 dark:ring-gray-600">
                                                <span class="text-lg font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                                   class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-400">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div class="space-y-2">
                                        <x-input-label for="name" :value="__('Full Name')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <x-text-input id="name" name="name" type="text" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                        </div>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <x-input-label for="email" :value="__('Email Address')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                </svg>
                                            </div>
                                            <x-text-input id="email" name="email" type="email" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" :value="old('email', $user->email)" required autocomplete="username" />
                                        </div>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Bio -->
                                <div class="space-y-2">
                                    <x-input-label for="bio" :value="__('Bio')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <textarea id="bio" name="bio" rows="3"
                                              class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                                              placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Location -->
                                    <div class="space-y-2">
                                        <x-input-label for="location" :value="__('Location')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <x-text-input id="location" name="location" type="text" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" :value="old('location', $user->location)" placeholder="City, Country" />
                                        </div>
                                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                    </div>

                                    <!-- Phone -->
                                    <div class="space-y-2">
                                        <x-input-label for="phone" :value="__('Phone Number')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </div>
                                            <x-text-input id="phone" name="phone" type="tel" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" :value="old('phone', $user->phone)" placeholder="+1 (555) 123-4567" />
                                        </div>
                                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Professional Links -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- LinkedIn -->
                                    <div class="space-y-2">
                                        <x-input-label for="linkedin_url" :value="__('LinkedIn Profile')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                            </div>
                                            <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" :value="old('linkedin_url', $user->linkedin_url)" placeholder="https://linkedin.com/in/username" />
                                        </div>
                                        <x-input-error :messages="$errors->get('linkedin_url')" class="mt-2" />
                                    </div>

                                    <!-- GitHub -->
                                    <div class="space-y-2">
                                        <x-input-label for="github_url" :value="__('GitHub Profile')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                </svg>
                                            </div>
                                            <x-text-input id="github_url" name="github_url" type="url" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" :value="old('github_url', $user->github_url)" placeholder="https://github.com/username" />
                                        </div>
                                        <x-input-error :messages="$errors->get('github_url')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Website -->
                                <div class="space-y-2">
                                    <x-input-label for="website" :value="__('Personal Website')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="website" name="website" type="url" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" :value="old('website', $user->website)" placeholder="https://yourwebsite.com" />
                                    </div>
                                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                                </div>

                                <!-- Experience Level -->
                                <div class="space-y-2">
                                    <x-input-label for="experience_level" :value="__('Experience Level')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <select id="experience_level" name="experience_level" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Select experience level</option>
                                        <option value="entry" {{ old('experience_level', $user->experience_level) == 'entry' ? 'selected' : '' }}>Entry Level (0-2 years)</option>
                                        <option value="junior" {{ old('experience_level', $user->experience_level) == 'junior' ? 'selected' : '' }}>Junior (2-4 years)</option>
                                        <option value="mid" {{ old('experience_level', $user->experience_level) == 'mid' ? 'selected' : '' }}>Mid Level (4-7 years)</option>
                                        <option value="senior" {{ old('experience_level', $user->experience_level) == 'senior' ? 'selected' : '' }}>Senior (7-10 years)</option>
                                        <option value="lead" {{ old('experience_level', $user->experience_level) == 'lead' ? 'selected' : '' }}>Lead (10+ years)</option>
                                        <option value="executive" {{ old('experience_level', $user->experience_level) == 'executive' ? 'selected' : '' }}>Executive</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('experience_level')" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4 pt-6">
                                    <x-primary-button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:ring-blue-500 px-8 py-3 rounded-xl font-semibold shadow-lg transform hover:scale-105 transition-all duration-200">
                                        {{ __('Save Changes') }}
                                    </x-primary-button>

                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                           class="text-sm text-green-600 dark:text-green-400 font-medium">
                                            {{ __('Saved successfully!') }}
                                        </p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Password Update -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-8">
                            <header class="flex items-center space-x-4 mb-8">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
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
                                    <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" autocomplete="current-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="update_password_password" :value="__('New Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="update_password_password" name="password" type="password" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4 pt-6">
                                    <x-primary-button class="bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 focus:ring-orange-500 px-8 py-3 rounded-xl font-semibold shadow-lg transform hover:scale-105 transition-all duration-200">
                                        {{ __('Update Password') }}
                                    </x-primary-button>

                                    @if (session('status') === 'password-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                           class="text-sm text-green-600 dark:text-green-400 font-medium">
                                            {{ __('Password updated successfully!') }}
                                        </p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Account Actions -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                                    </svg>
                                    <span class="font-medium">Dashboard</span>
                                </a>
                                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium">Resume Builder</span>
                                </a>
                                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    </svg>
                                    <span class="font-medium">Practice Interview</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Account Stats -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Account Statistics</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Member since</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('M Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total sessions</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->interviewSessions()->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Documents uploaded</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->userDocuments()->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Job applications</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->jobPostings()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-red-50/80 dark:bg-red-900/20 backdrop-blur-sm shadow-2xl rounded-2xl border border-red-200/50 dark:border-red-800/50 overflow-hidden">
                        <div class="p-6">
                            <header class="mb-4">
                                <h2 class="text-lg font-bold text-red-600 dark:text-red-400">
                                    {{ __('Delete Account') }}
                                </h2>
                                <p class="text-sm text-red-600 dark:text-red-400">
                                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                                </p>
                            </header>

                            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                                @csrf
                                @method('delete')

                                <div class="space-y-2">
                                    <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold text-red-700 dark:text-red-300" />
                                    <x-text-input id="password" name="password" type="password" class="block w-full rounded-xl border-red-300 dark:border-red-600 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20 dark:text-white" placeholder="{{ __('Enter your password to confirm') }}" />
                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                </div>

                                <x-button type="submit" class="w-full" negative icon="trash">
                                    {{ __('Delete Account') }}
                                </x-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
