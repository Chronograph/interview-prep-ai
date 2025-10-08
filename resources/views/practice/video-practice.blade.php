<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Practice Sessions</h1>
                <p class="text-gray-600">Improve your interview skills with targeted practice sessions.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Sessions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                            <p class="text-2xl font-bold text-gray-900">24</p>
                            <p class="text-sm text-green-600">+3 this week</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Score -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Avg. Score</p>
                            <p class="text-2xl font-bold text-gray-900">7.2/10</p>
                            <p class="text-sm text-green-600">+18% improvement</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Interview Ready -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Interview Ready</p>
                            <p class="text-2xl font-bold text-gray-900">5</p>
                            <p class="text-sm text-gray-500">Companies</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Need Practice -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Need Practice</p>
                            <p class="text-2xl font-bold text-gray-900">3</p>
                            <p class="text-sm text-orange-600">Companies requiring focus</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button class="py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600 bg-gray-50">
                            Upcoming Interviews
                        </button>
                        <button class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            By Role
                        </button>
                        <button class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            The Basics
                        </button>
                        <button class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Elevator Pitch
                        </button>
                        <button class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Skills
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Practice for Upcoming Interviews -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Practice for Upcoming Interviews</h2>
                    <p class="text-gray-600">Prepare for your scheduled interviews with company-specific practice sessions.</p>
                </div>

                <div class="space-y-4">
                    <!-- Google Practice Session Card -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <!-- Left side - Interview Details -->
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Company Avatar -->
                                <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">G</span>
                                </div>

                                <!-- Interview Info -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">Google</h3>
                                        <span class="text-gray-600">Senior Product Manager</span>
                                    </div>

                                    <!-- Date and Focus Areas -->
                                    <div class="flex items-center space-x-6 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Oct 4, 2025</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                            </svg>
                                            <span>Behavioral + Product Sense</span>
                                        </div>
                                    </div>

                                    <!-- Readiness Score -->
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-700">Readiness Score</span>
                                            <span class="text-sm font-semibold text-green-600">85% - Ready</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                                        </div>
                                    </div>

                                    <!-- Resource Buttons -->
                                    <div class="flex gap-3">
                                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Company Sheet
                                        </button>
                                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            Role Guide
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Right side - Status and Action -->
                            <div class="flex flex-col items-end space-y-3">
                                <!-- Practice Status -->
                                <span class="text-sm font-medium text-green-600">Practiced</span>

                                <!-- Start Practice Button -->
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v5a2 2 0 002 2h2a2 2 0 002-2v-5"></path>
                                    </svg>
                                    Start Practice
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Practice Session Card -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <!-- Left side - Interview Details -->
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Company Avatar -->
                                <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">M</span>
                                </div>

                                <!-- Interview Info -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">Meta</h3>
                                        <span class="text-gray-600">Product Manager, Growth</span>
                                    </div>

                                    <!-- Date and Focus Areas -->
                                    <div class="flex items-center space-x-6 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Oct 7, 2025</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                            </svg>
                                            <span>Product Design + Execution</span>
                                        </div>
                                    </div>

                                    <!-- Readiness Score -->
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-700">Readiness Score</span>
                                            <span class="text-sm font-semibold text-orange-600">62% - Needs Practice</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: 62%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right side - Status and Action -->
                            <div class="flex flex-col items-end space-y-3">
                                <!-- Practice Status -->
                                <span class="text-sm font-medium text-gray-500">Not Practiced</span>

                                <!-- Start Practice Button -->
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v5a2 2 0 002 2h2a2 2 0 002-2v-5"></path>
                                    </svg>
                                    Start Practice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
