<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <svg class="w-16 h-16 text-red-500" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="m61.1356 29.4611c0-16.3172-13.2441-29.5611-29.5611-29.5611s-29.5611 13.244-29.5611 29.5611c0 16.3172 13.244 29.5611 29.5611 29.5611s29.5611-13.244 29.5611-29.5611" fill="currentColor"/>
                        <path d="m60.1356 28.4611c0-15.7646-12.7965-28.5611-28.5611-28.5611s-28.5611 12.7965-28.5611 28.5611c0 15.7646 12.7965 28.5611 28.5611 28.5611s28.5611-12.7965 28.5611-28.5611" fill="white"/>
                        <path d="m39.3025 33.9423c0 6.2756-5.0869 11.3625-11.3625 11.3625s-11.3625-5.0869-11.3625-11.3625c0-6.2756 5.0869-11.3625 11.3625-11.3625s11.3625 5.0869 11.3625 11.3625z" fill="currentColor"/>
                    </svg>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <div class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                    <svg class="w-7 h-7 stroke-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900">Interview Prep AI</h2>

                                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                    Prepare for your next technical interview with AI-powered practice sessions, resume optimization, and personalized feedback.
                                </p>
                            </div>
                        </div>

                        <div class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                    <svg class="w-7 h-7 stroke-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443a55.381 55.381 0 015.25 2.882V15" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900">Get Started</h2>

                                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                    @auth
                                        Welcome back! <a href="{{ url('/dashboard') }}" class="text-red-500 hover:underline">Go to your dashboard</a> to continue your interview preparation.
                                    @else
                                        <a href="{{ route('register') }}" class="text-red-500 hover:underline">Create an account</a> or <a href="{{ route('login') }}" class="text-red-500 hover:underline">sign in</a> to start preparing for your interviews.
                                    @endauth
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 sm:text-left">
                        <div class="flex items-center gap-4">
                            <a href="https://laravel.com" class="group inline-flex items-center hover:text-gray-700 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                Laravel v{{ $laravelVersion }} (PHP v{{ $phpVersion }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>