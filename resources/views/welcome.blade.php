<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Hire Camp') }} - Ace Your Next Interview</title>
        <meta name="description" content="Master your interview skills with AI-powered practice sessions, personalized feedback, and comprehensive preparation tools.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white">
        <!-- Top Dark Gray Bar -->
        <div class="bg-gray-800 h-1"></div>

        <!-- Navigation -->
        <nav class="bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">H</span>
                                    </div>
                                    <span class="text-xl font-bold text-gray-900 hidden sm:block">
                                        Hire Camp
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Sign In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150">Get Started</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Light Gray Separator Line -->
        <div class="border-b border-gray-200"></div>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-50 via-white to-purple-50 py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                        Ace Your Next
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Interview</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                        Master your interview skills with AI-powered practice sessions, personalized feedback, and comprehensive preparation tools designed to boost your confidence.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition duration-150 shadow-lg hover:shadow-xl">
                                Continue Practicing
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition duration-150 shadow-lg hover:shadow-xl">
                                Start Free Trial
                            </a>
                            <a href="{{ route('login') }}" class="border-2 border-gray-300 hover:border-gray-400 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold transition duration-150">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Everything You Need to Succeed</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Comprehensive tools and AI-powered insights to help you prepare for any interview scenario.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center p-6 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 hover:shadow-lg transition duration-300">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">AI Mock Interviews</h3>
                        <p class="text-gray-600">Practice with realistic AI-powered interviews tailored to your target role and experience level.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center p-6 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 hover:shadow-lg transition duration-300">
                        <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Resume Optimization</h3>
                        <p class="text-gray-600">Get AI-powered suggestions to optimize your resume for specific job postings and industries.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center p-6 rounded-xl bg-gradient-to-br from-green-50 to-green-100 hover:shadow-lg transition duration-300">
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Performance Analytics</h3>
                        <p class="text-gray-600">Track your progress with detailed analytics and personalized improvement recommendations.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Trusted by Job Seekers</h2>
                    <p class="text-xl text-gray-600">See how Hire Camp has helped others land their dream jobs.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold">SJ</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Sarah Johnson</h4>
                                <p class="text-gray-600 text-sm">Software Engineer</p>
                            </div>
                        </div>
                        <p class="text-gray-700">"The AI mock interviews were incredibly realistic. I felt so much more confident going into my actual interviews!"</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 font-semibold">MC</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Michael Chen</h4>
                                <p class="text-gray-600 text-sm">Product Manager</p>
                            </div>
                        </div>
                        <p class="text-gray-700">"The resume optimization feature helped me get 3x more interview callbacks. Absolutely worth it!"</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-semibold">EP</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">Emily Parker</h4>
                                <p class="text-gray-600 text-sm">Data Scientist</p>
                            </div>
                        </div>
                        <p class="text-gray-700">"The detailed feedback and analytics helped me identify my weak points and improve systematically."</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Ace Your Next Interview?</h2>
                <p class="text-xl text-blue-100 mb-8">Join thousands of successful job seekers who've improved their interview skills with our AI-powered platform.</p>
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition duration-150 shadow-lg hover:shadow-xl">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition duration-150 shadow-lg hover:shadow-xl">
                        Start Your Free Trial
                    </a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <svg class="w-8 h-8 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold">Hire Camp</span>
                    </div>
                    <div class="text-gray-400 text-sm">
                        © {{ date('Y') }} Hire Camp. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
