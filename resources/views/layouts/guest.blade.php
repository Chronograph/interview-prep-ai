<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-gray-900 antialiased">
    <!-- Background Pattern -->
    <div class="fixed inset-0 bg-gradient-to-br from-orange-50 via-white to-orange-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f97316" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] dark:bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23374151" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo Section -->
        <div class="animate-fade-in-up">
            <a href="/" class="group">
                <div class="flex items-center justify-center w-20 h-20 mx-auto mb-8 bg-white dark:bg-gray-800 rounded-full shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-105 animate-pulse">
                    <x-application-logo class="w-12 h-12 fill-current text-orange-600 dark:text-orange-400" />
                </div>
            </a>
        </div>

        <!-- Main Content Card -->
        <div class="animate-fade-in-up w-full sm:max-w-md mt-6 px-8 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20 dark:border-gray-700/50">
            {{ $slot }}
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>