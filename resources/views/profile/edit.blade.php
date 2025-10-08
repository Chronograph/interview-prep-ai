@php
    use Illuminate\Support\Facades\Storage;
@endphp

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

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <!-- Profile Photo Section -->
                            <div class="space-y-4">
                                <x-input-label :value="__('Profile Photo')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                <div class="flex items-center space-x-6">
                                    <div class="shrink-0">
                                        @if($user->profile_photo_path)
                                            <img class="h-20 w-20 object-cover rounded-full border-4 border-white shadow-lg" src="{{ Storage::url($user->profile_photo_path) }}" alt="Profile photo" />
                                        @else
                                            <div class="h-20 w-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-400 dark:hover:file:bg-blue-900/40">
                                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                            </div>

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
                                </div>
                            </div>

                            <!-- Bio Section -->
                            <div class="space-y-2">
                                <x-input-label for="bio" :value="__('Bio')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                <textarea id="bio" name="bio" rows="4" class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                            </div>

                            <!-- Contact Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="location" :value="__('Location')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="location" name="location" type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('location', $user->location)" placeholder="City, State/Country" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('location')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="phone" :value="__('Phone Number')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="phone" name="phone" type="tel" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('phone', $user->phone)" placeholder="+1 (555) 123-4567" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>
                            </div>

                            <!-- Professional Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="current_role" :value="__('Current Role')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="current_role" name="current_role" type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('current_role', $user->current_role)" placeholder="Software Engineer, Product Manager, etc." />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('current_role')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="years_experience" :value="__('Years of Experience')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="years_experience" name="years_experience" type="number" min="0" max="50" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('years_experience', $user->years_experience)" placeholder="5" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('years_experience')" />
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Social & Professional Links</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <x-input-label for="linkedin_url" :value="__('LinkedIn Profile')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                            </div>
                                            <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('linkedin_url', $user->linkedin_url)" placeholder="https://linkedin.com/in/yourprofile" />
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
                                    </div>

                                    <div class="space-y-2">
                                        <x-input-label for="github_url" :value="__('GitHub Profile')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                </svg>
                                            </div>
                                            <x-text-input id="github_url" name="github_url" type="url" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('github_url', $user->github_url)" placeholder="https://github.com/yourusername" />
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('github_url')" />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="portfolio_url" :value="__('Portfolio Website')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                                            </svg>
                                        </div>
                                        <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('portfolio_url', $user->portfolio_url)" placeholder="https://yourportfolio.com" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('portfolio_url')" />
                                </div>
                            </div>

                            <!-- Skills and Target Roles -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Career Preferences</h3>

                                <div class="space-y-2">
                                    <x-input-label for="target_roles" :value="__('Target Roles (comma-separated)')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="target_roles" name="target_roles" type="text" class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('target_roles', is_array($user->target_roles) ? implode(', ', $user->target_roles) : $user->target_roles)" placeholder="Software Engineer, Product Manager, Data Scientist" />
                                    <x-input-error class="mt-2" :messages="$errors->get('target_roles')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="skills" :value="__('Skills (comma-separated)')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="skills" name="skills" type="text" class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" :value="old('skills', is_array($user->skills) ? implode(', ', $user->skills) : $user->skills)" placeholder="JavaScript, Python, React, Node.js, AWS" />
                                    <x-input-error class="mt-2" :messages="$errors->get('skills')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="preferred_interview_types" :value="__('Preferred Interview Types')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        @php
                                            $interviewTypes = [
                                                'technical' => 'Technical',
                                                'behavioral' => 'Behavioral',
                                                'case_study' => 'Case Study',
                                                'system_design' => 'System Design',
                                                'coding' => 'Coding'
                                            ];
                                            $userPreferences = old('preferred_interview_types', $user->preferred_interview_types ?? []);
                                        @endphp
                                        @foreach($interviewTypes as $value => $label)
                                            <label class="flex items-center space-x-2 p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition duration-200">
                                                <input type="checkbox" name="preferred_interview_types[]" value="{{ $value }}"
                                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                                       {{ in_array($value, $userPreferences) ? 'checked' : '' }}>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('preferred_interview_types')" />
                                </div>
                            </div>

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
                                            <x-button form="send-verification" size="sm" class="mt-1 text-amber-700 dark:text-amber-300 hover:text-amber-900 dark:hover:text-amber-100 underline">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </x-button>
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

                            <div class="flex items-center justify-between pt-6">
                                <div class="flex items-center space-x-4">
                                    <x-button type="submit" success icon="check">
                                        {{ __('Save Changes') }}
                                    </x-button>
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
                                    <x-button type="submit" primary icon="check">
                                        {{ __('Update Password') }}
                                    </x-button>
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

                        <x-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                            negative
                            icon="trash"
                        >
                            {{ __('Delete Account') }}
                        </x-button>

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
                                     <x-button type="button" x-on:click="$dispatch('close')" secondary>
                                         {{ __('Cancel') }}
                                     </x-button>

                                     <x-button type="submit" negative icon="trash">
                                         {{ __('Delete Account') }}
                                     </x-button>
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
