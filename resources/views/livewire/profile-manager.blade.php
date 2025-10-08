@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-50/80 backdrop-blur-xl border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('password_updated'))
            <div class="bg-blue-50/80 backdrop-blur-xl border border-blue-200 text-blue-700 px-4 py-3 rounded-2xl mb-6">
                {{ session('password_updated') }}
            </div>
        @endif

        <!-- Profile Overview Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50">
            <div class="p-8">
                <div class="flex items-center space-x-6">
                    <!-- Profile Photo -->
                    <div class="relative">
                        @if($profile_photo_path)
                            <img class="w-24 h-24 rounded-full object-cover ring-4 ring-white dark:ring-gray-800 shadow-lg"
                                 src="{{ Storage::url($profile_photo_path) }}"
                                 alt="{{ $name }}">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 shadow-lg">
                                <span class="text-2xl font-bold text-white">{{ substr($name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-2 border-white dark:border-gray-800"></div>
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $email }}</p>
                        @if($bio)
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">{{ $bio }}</p>
                        @endif

                        <!-- Profile Completion -->
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Completion</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->profile_completion_percentage ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                @php $completion = $user->profile_completion_percentage ?? 0; @endphp
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
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50">
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

                        <div wire:loading.class="opacity-50" class="space-y-6">
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Full Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <input wire:model="name" type="text" id="name"
                                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                                               placeholder="Enter your full name" required>
                                    </div>
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Email -->
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                        </div>
                                        <input wire:model="email" type="email" id="email"
                                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                                               placeholder="Enter your email address" required>
                                    </div>
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Location -->
                                <div class="space-y-2">
                                    <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Location</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <input wire:model="location" type="text" id="location"
                                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('location') border-red-500 @enderror"
                                               placeholder="City, Country">
                                    </div>
                                    @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Phone -->
                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Phone Number</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <input wire:model="phone" type="tel" id="phone"
                                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('phone') border-red-500 @enderror"
                                               placeholder="+1 (555) 123-4567">
                                    </div>
                                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Professional Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="current_title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Current Role</label>
                                    <input wire:model="current_title" type="text" id="current_title"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('current_title') border-red-500 @enderror"
                                           placeholder="Software Engineer, Product Manager, etc.">
                                    @error('current_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label for="years_experience" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Years of Experience</label>
                                    <input wire:model="years_experience" type="number" id="years_experience" min="0" max="50"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('years_experience') border-red-500 @enderror"
                                           placeholder="5">
                                    @error('years_experience') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Professional Links -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="linkedin_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">LinkedIn Profile</label>
                                    <input wire:model="linkedin_url" type="url" id="linkedin_url"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('linkedin_url') border-red-500 @enderror"
                                           placeholder="https://linkedin.com/in/username">
                                    @error('linkedin_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label for="github_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">GitHub Profile</label>
                                    <input wire:model="github_url" type="url" id="github_url"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('github_url') border-red-500 @enderror"
                                           placeholder="https://github.com/username">
                                    @error('github_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="portfolio_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Website</label>
                                <input wire:model="portfolio_url" type="url" id="portfolio_url"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('portfolio_url') border-red-500 @enderror"
                                       placeholder="https://yourwebsite.com">
                                @error('portfolio_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Skills Input -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Skills</label>
                                @foreach($skills as $index => $skill)
                                    <div class="flex gap-2">
                                        <input wire:model="skills.{{ $index }}" type="text"
                                               class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('skills.' . $index) border-red-500 @enderror"
                                               placeholder="e.g., JavaScript, Python, React">
                                        @if(count($skills) > 1)
                                            <button wire:click="removeSkill({{ $index }})" type="button"
                                                    class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                                <button wire:click="addSkill" type="button"
                                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-xl">
                                    + Add Skill
                                </button>
                            </div>

                            <button wire:click="saveProfile"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Password Update -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50">
                    <div class="p-8">
                        <header class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-4">
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
                            </div>
                            <button wire:click="togglePasswordSection"
                                    class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-xl">
                                {{ $showPasswordSection ? 'Cancel' : 'Update Password' }}
                            </button>
                        </header>

                        @if($showPasswordSection)
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label for="current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Current Password</label>
                                    <input wire:model="current_password" type="password" id="current_password"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:text-white @error('current_password') border-red-500 @enderror"
                                           autocomplete="current-password">
                                    @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">New Password</label>
                                    <input wire:model="password" type="password" id="password"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror"
                                           autocomplete="new-password">
                                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Confirm Password</label>
                                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:text-white @error('password_confirmation') border-red-500 @enderror"
                                           autocomplete="new-password">
                                    @error('password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <button wire:click="updatePassword"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Password
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Account Stats -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/50">
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
            </div>
        </div>
    </div>
</div>
