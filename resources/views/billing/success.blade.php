<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Successful - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-base-200 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body text-center">
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="rounded-full bg-success/10 p-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <h1 class="text-3xl font-bold text-base-content mb-2">
                        Welcome Aboard!
                    </h1>
                    <p class="text-base-content/70 mb-6">
                        Your subscription has been activated successfully. You now have access to all premium features.
                    </p>

                    <!-- What's Next -->
                    <div class="bg-base-200 rounded-lg p-4 mb-6 text-left">
                        <h3 class="font-semibold mb-2">What's Next?</h3>
                        <ul class="space-y-2 text-sm text-base-content/70">
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Start practicing with AI mock interviews</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Upload and optimize your resume</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Track your job applications</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-block">
                            Go to Dashboard
                        </a>
                        <a href="{{ route('billing.index') }}" class="btn btn-outline btn-block">
                            View Billing
                        </a>
                    </div>
                </div>
            </div>

            <!-- Support Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-base-content/70">
                    Questions? <a href="#" class="link link-primary">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

