<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        Billing & Subscription 💳
                    </h1>
                    <p class="text-lg text-gray-600">Manage your subscription, payment methods, and invoices</p>
                </div>
                @if(isset($organization) && $organization && $organization->hasPaymentMethod())
                    <div class="flex gap-3">
                        <x-button wire:click="openCustomerPortal" primary lg icon="cog-6-tooth">
                            Open Billing Portal
                        </x-button>
                    </div>
                @endif
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Current Plan Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Current Plan</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ ($plans[$currentPlan]['name'] ?? null) ?? 'Free' }}</p>
                        @if(isset($currentSubscription) && $currentSubscription && $currentSubscription->active())
                            <p class="text-sm text-green-600 mt-1 flex items-center gap-1">
                                <x-icon name="check-circle" class="w-4 h-4" />
                                Active
                            </p>
                        @else
                            <p class="text-sm text-orange-600 mt-1 flex items-center gap-1">
                                <x-icon name="exclamation-triangle" class="w-4 h-4" />
                                Free Plan
                            </p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <x-icon name="credit-card" class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
            </div>

            <!-- Monthly Cost Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Monthly Cost</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">${{ ($plans[$currentPlan]['price'] ?? 0) }}<span class="text-xl text-gray-500">/mo</span></p>
                        @if(($currentPlan ?? 'free') !== 'free')
                            <p class="text-sm text-green-600 mt-1 flex items-center gap-1">
                                <x-icon name="calendar" class="w-4 h-4" />
                                Billed monthly
                            </p>
                        @else
                            <p class="text-sm text-green-600 mt-1 flex items-center gap-1">
                                <x-icon name="gift" class="w-4 h-4" />
                                No charge
                            </p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <x-icon name="currency-dollar" class="w-6 h-6 text-green-600" />
                    </div>
                </div>
            </div>

            <!-- Payment Methods Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Payment Methods</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ isset($paymentMethods) && $paymentMethods ? $paymentMethods->count() : 0 }}</p>
                        @if(isset($paymentMethods) && $paymentMethods && $paymentMethods->count() > 0)
                            <p class="text-sm text-green-600 mt-1 flex items-center gap-1">
                                <x-icon name="check-circle" class="w-4 h-4" />
                                Configured
                            </p>
                        @else
                            <p class="text-sm text-orange-600 mt-1 flex items-center gap-1">
                                <x-icon name="exclamation-triangle" class="w-4 h-4" />
                                None added
                            </p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <x-icon name="credit-card" class="w-6 h-6 text-purple-600" />
                    </div>
                </div>
            </div>

            <!-- Next Billing Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Next Billing</p>
                        @if(isset($upcomingInvoice) && $upcomingInvoice)
                            <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($upcomingInvoice->amount_due / 100, 2) }}</p>
                            <p class="text-sm text-orange-600 mt-1 flex items-center gap-1">
                                <x-icon name="calendar" class="w-4 h-4" />
                                {{ $upcomingInvoice->date()->format('M j') }}
                            </p>
                        @else
                            <p class="text-3xl font-bold text-gray-900 mt-1">--</p>
                            <p class="text-sm text-gray-500 mt-1">No upcoming billing</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <x-icon name="calendar" class="w-6 h-6 text-orange-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Subscription Status -->
        @if(isset($currentSubscription) && $currentSubscription && $currentSubscription->active())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-icon name="check-circle" class="w-6 h-6 text-green-600" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Subscription Active</h3>
                                <p class="text-gray-700">You're on the <strong>{{ ($plans[$currentPlan]['name'] ?? null) ?? 'Unknown' }}</strong> plan. {{ $currentSubscription->stripe_status === 'active' ? 'Your subscription is active and you have full access to all features.' : 'Your subscription is being processed.' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <x-button wire:click="openCustomerPortal" outline icon="cog-6-tooth">
                                Manage Subscription
                            </x-button>
                            @if($currentSubscription->onGracePeriod())
                                <x-button wire:click="resumeSubscription" primary icon="play">
                                    Resume Subscription
                                </x-button>
                            @elseif(!$currentSubscription->canceled())
                                <x-button wire:click="cancelSubscription" secondary icon="pause">
                                    Cancel Subscription
                                </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Plan Selection -->
        <div class="mb-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Choose Your Plan</h2>
                <p class="text-gray-600">Select the plan that best fits your interview preparation needs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(($plans ?? []) as $key => $plan)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 {{ isset($plan['popular']) && $plan['popular'] ? 'ring-2 ring-blue-500 relative' : '' }}">
                        @if(isset($plan['popular']) && $plan['popular'])
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600 text-white">
                                    Most Popular
                                </span>
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $plan['name'] }}</h3>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-gray-900">${{ $plan['price'] }}</div>
                                    <div class="text-sm text-gray-500">per month</div>
                                </div>
                            </div>

                            <p class="text-gray-600 mb-6">{{ $plan['description'] }}</p>

                            <div class="space-y-3 mb-6">
                                @foreach(($plan['features'] ?? []) as $feature)
                                    <div class="flex items-center gap-2">
                                        <x-icon name="check" class="w-4 h-4 text-green-500" />
                                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <x-button
                                wire:click="subscribe('{{ $key }}')"
                                :primary="isset($plan['popular']) && $plan['popular']"
                                :outline="!isset($plan['popular']) || !$plan['popular']"
                                class="w-full"
                                icon="{{ $key === 'free' ? 'gift' : 'credit-card' }}"
                            >
                                @if($key === 'free')
                                    Downgrade to Free
                                @else
                                    {{ ($currentPlan ?? 'free') !== 'free' ? 'Switch Plan' : 'Subscribe' }}
                                @endif
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Methods & Billing Details -->
        @if(isset($organization) && $organization && $organization->hasPaymentMethod())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Payment Methods -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h3>
                        @if(isset($paymentMethods) && $paymentMethods && count($paymentMethods) > 0)
                            <div class="space-y-3">
                                @foreach($paymentMethods as $paymentMethod)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                                <x-icon name="credit-card" class="w-4 h-4 text-gray-600" />
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $paymentMethod->card->brand }} •••• {{ $paymentMethod->card->last4 }}</p>
                                                <p class="text-sm text-gray-500">Expires {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}</p>
                                            </div>
                                        </div>
                                        @if($paymentMethod->id === $organization->defaultPaymentMethod()?->id)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <x-icon name="credit-card" class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <p class="text-gray-600">No payment methods added</p>
                            </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <x-button wire:click="openCustomerPortal" outline icon="plus" class="w-full">
                                Manage Payment Methods
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Invoice -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Invoice</h3>
                        @if(isset($upcomingInvoice) && $upcomingInvoice)
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Amount Due</span>
                                    <span class="text-lg font-semibold text-gray-900">${{ number_format($upcomingInvoice->amount_due / 100, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Due Date</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $upcomingInvoice->date()->format('F j, Y') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <x-icon name="document-text" class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <p class="text-gray-600">No upcoming invoice</p>
                            </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <x-button wire:click="openCustomerPortal" outline icon="eye" class="w-full">
                                View All Invoices
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Invoices -->
            @if(isset($invoices) && $invoices && count($invoices) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Invoices</h3>
                        <div class="space-y-3">
                            @foreach($invoices->take(5) as $invoice)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                            <x-icon name="document-text" class="w-4 h-4 text-gray-600" />
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Invoice #{{ $invoice->number }}</p>
                                            <p class="text-sm text-gray-500">{{ $invoice->date()->format('F j, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-medium text-gray-900">${{ number_format($invoice->amount_paid / 100, 2) }}</span>
                                        <x-button wire:click="downloadInvoice('{{ $invoice->id }}')" sm outline icon="arrow-down-tray">
                                            Download
                                        </x-button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Billing Portal Access -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 p-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <x-icon name="cog-6-tooth" class="w-6 h-6 text-blue-600" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Need to update your payment information or view more billing details?</h3>
                            <p class="text-gray-700">Access our secure billing portal to manage your subscription, payment methods, and download invoices.</p>
                        </div>
                    </div>
                    <x-button wire:click="openCustomerPortal" primary lg icon="arrow-top-right-on-square" class="shadow-lg hover:shadow-xl transition-shadow">
                        Open Billing Portal
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
