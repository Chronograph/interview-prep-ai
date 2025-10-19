@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Your Profile</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your professional profile and career preferences</p>
            </div>
            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-medium">
                Profile Strength: {{ $profile_completion_percentage }}%
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-6">
            <!-- 1. Your Profile (Personal Details) Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <!-- Profile Photo -->
                        <div class="relative">
                            @if($profile_photo_path)
                                <img class="w-20 h-20 rounded-full object-cover"
                                     src="{{ Storage::url($profile_photo_path) }}"
                                     alt="{{ $name }}">
                            @else
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">{{ substr($name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute -bottom-1 -right-1 bg-green-500 w-5 h-5 rounded-full border-2 border-white"></div>
                        </div>

                        <!-- Profile Info -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $name ?: 'Your Name' }}</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400">
                                {{ $current_title ?: 'Your Title' }} | {{ $current_company ?: 'Your Company' }}
                            </p>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                @if($location)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $location }}
                                    </span>
                                @endif
                                @if($email)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                        {{ $email }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <button wire:click="$set('showPersonalDetails', true)" class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </button>
                </div>

                <!-- External Links -->
                <div class="flex space-x-4">
                    @if($portfolio_url)
                        <a href="{{ $portfolio_url }}" target="_blank" class="flex items-center text-purple-600 hover:text-purple-700">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            Portfolio
                        </a>
                    @endif
                    @if($github_url)
                        <a href="{{ $github_url }}" target="_blank" class="flex items-center text-purple-600 hover:text-purple-700">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            GitHub
                        </a>
                    @endif
                    @if($linkedin_url)
                        <a href="{{ $linkedin_url }}" target="_blank" class="flex items-center text-purple-600 hover:text-purple-700">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </a>
                    @endif
                </div>
            </div>

            <!-- Personal Details Edit Form -->
            @if($showPersonalDetails ?? false)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Personal Details</h3>
                        <button wire:click="$set('showPersonalDetails', false)" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveProfile" class="space-y-6">
                        <!-- Profile Photo Upload -->
                        <div class="space-y-4">
                            <label for="profile_photo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Profile Photo</label>
                            <div class="flex items-center space-x-6">
                                @if($profile_photo_path)
                                    <img class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-600"
                                         src="{{ Storage::url($profile_photo_path) }}"
                                         alt="{{ $name }}">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-gray-300 dark:ring-gray-600">
                                        <span class="text-lg font-bold text-white">{{ substr($name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input wire:model="profile_photo" type="file" id="profile_photo" accept="image/*"
                                           class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-400">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                                    @error('profile_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Full Name</label>
                                <input wire:model="name" type="text" id="name"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                                       placeholder="Enter your full name" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Email Address</label>
                                <input wire:model="email" type="email" id="email"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                                       placeholder="Enter your email address" required>
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Title -->
                            <div class="space-y-2">
                                <label for="current_title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Current Title</label>
                                <input wire:model="current_title" type="text" id="current_title"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('current_title') border-red-500 @enderror"
                                       placeholder="e.g., Senior Product Designer">
                                @error('current_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Current Company -->
                            <div class="space-y-2">
                                <label for="current_company" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Current Company</label>
                                <input wire:model="current_company" type="text" id="current_company"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('current_company') border-red-500 @enderror"
                                       placeholder="e.g., TechVision Inc.">
                                @error('current_company') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="space-y-2">
                            <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Location</label>
                            <input wire:model="location" type="text" id="location"
                                   class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('location') border-red-500 @enderror"
                                   placeholder="e.g., San Francisco, CA">
                            @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- External Links -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">External Links</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- LinkedIn URL -->
                                <div class="space-y-2">
                                    <label for="linkedin_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">LinkedIn Profile</label>
                                    <input wire:model="linkedin_url" type="url" id="linkedin_url"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('linkedin_url') border-red-500 @enderror"
                                           placeholder="https://linkedin.com/in/username">
                                    @error('linkedin_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- GitHub URL -->
                                <div class="space-y-2">
                                    <label for="github_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">GitHub Profile</label>
                                    <input wire:model="github_url" type="url" id="github_url"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('github_url') border-red-500 @enderror"
                                           placeholder="https://github.com/username">
                                    @error('github_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Portfolio URL -->
                            <div class="space-y-2">
                                <label for="portfolio_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Portfolio/Personal Website</label>
                                <input wire:model="portfolio_url" type="url" id="portfolio_url"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('portfolio_url') border-red-500 @enderror"
                                       placeholder="https://yourwebsite.com">
                                @error('portfolio_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="space-y-2">
                            <label for="bio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Bio</label>
                            <textarea wire:model="bio" id="bio" rows="3"
                                      class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none @error('bio') border-red-500 @enderror"
                                      placeholder="Tell us about yourself..."></textarea>
                            @error('bio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="$set('showPersonalDetails', false)"
                                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 2. Career Preferences Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Career Preferences</h2>
                            <p class="text-gray-600 dark:text-gray-400">Personalize your interview practice and job recommendations</p>
                        </div>
                    </div>
                    <button wire:click="$set('showCareerPreferences', true)" class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Update
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Target Industries -->
                    <div>
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="w-6 h-6 bg-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Target Industries</h3>
                        </div>
                        <div class="space-y-2">
                            @if(!empty($target_industries))
                                @foreach($target_industries as $industry)
                                    <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">{{ $industry }}</span>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm">No industries selected</p>
                            @endif
                        </div>
                    </div>

                    <!-- Target Companies -->
                    <div>
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="w-6 h-6 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Target Companies</h3>
                        </div>
                        <div class="space-y-2">
                            @if(!empty($target_companies))
                                @foreach($target_companies as $company)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ $company }}</span>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm">No companies selected</p>
                            @endif
                        </div>
                    </div>

                    <!-- Target Roles -->
                    <div>
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="w-6 h-6 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Target Roles</h3>
                        </div>
                        <div class="space-y-2">
                            @if(!empty($target_roles))
                                @foreach($target_roles as $role)
                                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $role }}</span>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm">No roles selected</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Professional Summary Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Professional Summary</h2>
                </div>
                @if($professional_summary)
                    <p class="text-gray-700 dark:text-gray-300">{{ $professional_summary }}</p>
                @else
                    <p class="text-gray-500 italic">No professional summary provided</p>
                @endif
            </div>

            <!-- 4. Work Experience Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Work Experience</h2>
                    </div>
                    <button wire:click="addWorkExperience" class="flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Experience
                    </button>
                </div>

                @if(!empty($work_experience))
                    <div class="space-y-6">
                        @foreach($work_experience as $index => $experience)
                            <div class="border-l-4 border-purple-500 pl-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $experience['title'] ?: 'Job Title' }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 font-medium">
                                            {{ $experience['company'] ?: 'Company Name' }}
                                        </p>
                                        @if($experience['duration'])
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $experience['duration'] }}</p>
                                        @endif
                                        @if($experience['description'])
                                            <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $experience['description'] }}</p>
                                        @endif
                                        @if(!empty($experience['achievements']))
                                            <div class="mt-3">
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Key Achievements:</h4>
                                                <ul class="space-y-1">
                                                    @foreach($experience['achievements'] as $achievement)
                                                        @if($achievement)
                                                            <li class="text-gray-700 dark:text-gray-300 text-sm">• {{ $achievement }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <button wire:click="removeWorkExperience({{ $index }})" class="ml-4 text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">No work experience added yet</p>
                        <button wire:click="addWorkExperience" class="mt-4 text-purple-600 hover:text-purple-700 font-medium">Add your first experience</button>
                    </div>
                @endif
            </div>

            <!-- 5. Skills Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Skills</h2>
                </div>
                @if(!empty($skills))
                    <div class="flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                            @if($skill)
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">{{ $skill }}</span>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">No skills added yet</p>
                @endif
            </div>

            <!-- 6. Education Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Education</h2>
                    </div>
                    <button wire:click="addEducation" class="flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Education
                    </button>
                </div>

                @if(!empty($education))
                    <div class="space-y-4">
                        @foreach($education as $index => $edu)
                            <div class="border-l-4 border-purple-500 pl-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $edu['degree'] ?: 'Degree' }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $edu['institution'] ?: 'Institution' }}
                                        </p>
                                        @if($edu['year'])
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Class of {{ $edu['year'] }}</p>
                                        @endif
                                        @if($edu['gpa'])
                                            <p class="text-sm text-gray-500 dark:text-gray-400">GPA: {{ $edu['gpa'] }}</p>
                                        @endif
                                    </div>
                                    <button wire:click="removeEducation({{ $index }})" class="ml-4 text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">No education added yet</p>
                        <button wire:click="addEducation" class="mt-4 text-purple-600 hover:text-purple-700 font-medium">Add your education</button>
                    </div>
                @endif
            </div>

            <!-- 7. Certifications Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Certifications</h2>
                    </div>
                    <button wire:click="addCertification" class="flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Certification
                    </button>
                </div>

                @if(!empty($certifications))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($certifications as $index => $cert)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $cert['name'] ?: 'Certification Name' }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $cert['issuer'] ?: 'Issuer' }}</p>
                                        @if($cert['year'])
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $cert['year'] }}</p>
                                        @endif
                                    </div>
                                    <button wire:click="removeCertification({{ $index }})" class="ml-4 text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">No certifications added yet</p>
                        <button wire:click="addCertification" class="mt-4 text-purple-600 hover:text-purple-700 font-medium">Add your first certification</button>
                    </div>
                @endif
            </div>

            <!-- 8. Personal Projects Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Personal Projects</h2>
                    </div>
                    <button wire:click="addProject" class="flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Project
                    </button>
                </div>

                @if(!empty($projects))
                    <div class="space-y-6">
                        @foreach($projects as $index => $project)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $project['name'] ?: 'Project Name' }}
                                        </h3>
                                        @if($project['description'])
                                            <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $project['description'] }}</p>
                                        @endif
                                        @if(!empty($project['technologies']))
                                            <div class="mt-3">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($project['technologies'] as $tech)
                                                        @if($tech)
                                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">{{ $tech }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if($project['url'])
                                            <a href="{{ $project['url'] }}" target="_blank" class="inline-flex items-center mt-3 text-purple-600 hover:text-purple-700">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                View Project
                                            </a>
                                        @endif
                                    </div>
                                    <button wire:click="removeProject({{ $index }})" class="ml-4 text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">No projects added yet</p>
                        <button wire:click="addProject" class="mt-4 text-purple-600 hover:text-purple-700 font-medium">Add your first project</button>
                    </div>
                @endif
            </div>

            <!-- Auto-populate from Resume Button -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Auto-populate from Resume</h3>
                        <p class="text-blue-700 mt-1">Automatically fill your profile using data from your latest uploaded resume</p>
                    </div>
                    <button wire:click="autoPopulateFromResume" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                        Auto-populate Profile
                    </button>
                </div>
            </div>

            <!-- Save Profile Button -->
            <div class="flex justify-end">
                <button wire:click="saveProfile" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold">
                    Save Profile
                </button>
            </div>
        </div>
    </div>
</div>