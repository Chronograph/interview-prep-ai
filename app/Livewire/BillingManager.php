<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.app')]
#[Title('Billing & Subscription')]
class BillingManager extends Component
{
    use WireUiActions;

    public $plans = [];

    public $currentSubscription = null;

    public $upcomingInvoice = null;

    public $paymentMethods = [];

    public $invoices = [];

    public $selectedPlan = null;

    public $organization = null;

    public function mount()
    {
        try {
            $this->loadPlans();
            $this->ensureUserHasOrganization();
            $this->loadSubscriptionData();
        } catch (\Exception $e) {
            logger()->error('BillingManager mount error: '.$e->getMessage());
            $this->notification()->error('Error', 'Failed to load billing information. Please try again.');
        }
    }

    protected function ensureUserHasOrganization()
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $organization = $user->primaryOrganization();

        if (! $organization) {
            try {
                // Create a default organization for the user
                $organization = \App\Models\Organization::create([
                    'name' => $user->name."'s Organization",
                    'owner_id' => $user->id,
                    'is_active' => true,
                ]);

                // Add the user as a member
                $organization->members()->attach($user->id, [
                    'role' => 'owner',
                    'joined_at' => now(),
                ]);
            } catch (\Exception $e) {
                logger()->error('Failed to create organization: '.$e->getMessage());
            }
        }

        $this->organization = $organization;
    }

    public function loadPlans()
    {
        $this->plans = config('plans.plans', [
            'free' => [
                'name' => 'Free',
                'price' => 0,
                'description' => 'Basic features for individuals',
                'price_id' => null,
            ],
        ]);

        if (empty($this->plans)) {
            $this->plans = [
                'free' => [
                    'name' => 'Free',
                    'price' => 0,
                    'description' => 'Basic features for individuals',
                    'price_id' => null,
                ],
            ];
        }
    }

    public function loadSubscriptionData()
    {
        if (! $this->organization) {
            $this->currentSubscription = null;
            $this->upcomingInvoice = null;
            $this->paymentMethods = collect();
            $this->invoices = collect();

            return;
        }

        // Get current subscription
        $this->currentSubscription = $this->organization->subscription('default');

        // Get upcoming invoice if subscribed
        if ($this->currentSubscription && $this->currentSubscription->active()) {
            try {
                $this->upcomingInvoice = $this->organization->upcomingInvoice();
            } catch (\Exception $e) {
                $this->upcomingInvoice = null;
            }
        }

        // Get payment methods
        $this->paymentMethods = $this->organization->paymentMethods();

        // Get invoices
        $this->invoices = $this->organization->invoices();
    }

    public function subscribe($plan)
    {
        $user = Auth::user();

        if (! $this->organization) {
            $this->notification()->error('No Organization', 'You must be part of an organization to manage subscriptions.');

            return;
        }

        if (! $this->organization->isOwner($user) && ! $this->organization->hasRole($user, 'admin')) {
            $this->notification()->error('Unauthorized', 'Only organization owners and admins can manage subscriptions.');

            return;
        }

        $planConfig = $this->plans[$plan] ?? null;

        if (! $planConfig) {
            $this->notification()->error('Invalid Plan', 'The selected plan does not exist.');

            return;
        }

        // Free plan - no Stripe subscription needed
        if ($plan === 'free') {
            if ($this->organization->subscribed('default')) {
                $this->organization->subscription('default')->cancelNow();
            }
            $this->notification()->success('Plan Changed!', 'You are now on the Free plan.');
            $this->loadSubscriptionData();

            return;
        }

        $priceId = $planConfig['price_id'];

        if (! $priceId) {
            $this->notification()->error('Configuration Error', 'This plan is not properly configured. Please contact support.');

            return;
        }

        try {
            // Check if organization has a payment method
            if (! $this->organization->hasDefaultPaymentMethod()) {
                // Redirect to Stripe Checkout
                return redirect()->route('billing.checkout', ['plan' => $plan]);
            }

            // Create or update subscription
            if ($this->organization->subscribed('default')) {
                // Swap to new plan
                $this->organization->subscription('default')->swap($priceId);
                $this->notification()->success('Plan Updated!', 'Your subscription has been updated to '.$planConfig['name'].'.');
            } else {
                // Create new subscription
                $this->organization->newSubscription('default', $priceId)->create();
                $this->notification()->success('Subscribed!', 'You are now subscribed to '.$planConfig['name'].'.');
            }

            $this->loadSubscriptionData();
        } catch (\Exception $e) {
            $this->notification()->error('Error', 'Failed to process subscription: '.$e->getMessage());
        }
    }

    public function resumeSubscription()
    {
        if (! $this->organization) {
            $this->notification()->error('No Organization', 'You must be part of an organization to manage subscriptions.');

            return;
        }

        if ($this->organization->subscription('default')->onGracePeriod()) {
            $this->organization->subscription('default')->resume();
            $this->notification()->success('Subscription Resumed!', 'Your subscription has been resumed.');
            $this->loadSubscriptionData();
        }
    }

    public function cancelSubscription()
    {
        if (! $this->organization) {
            $this->notification()->error('No Organization', 'You must be part of an organization to manage subscriptions.');

            return;
        }

        if ($this->organization->subscribed('default')) {
            $this->organization->subscription('default')->cancel();
            $this->notification()->success(
                'Subscription Cancelled',
                'Your subscription will end at the end of your billing period.'
            );
            $this->loadSubscriptionData();
        }
    }

    public function openCustomerPortal()
    {
        if (! $this->organization) {
            $this->notification()->error('No Organization', 'You must be part of an organization to access the billing portal.');

            return;
        }

        try {
            return redirect($this->organization->billingPortalUrl(route('billing.index')));
        } catch (\Exception $e) {
            $this->notification()->error('Error', 'Failed to open customer portal: '.$e->getMessage());
        }
    }

    public function downloadInvoice($invoiceId)
    {
        if (! $this->organization) {
            $this->notification()->error('No Organization', 'You must be part of an organization to download invoices.');

            return;
        }

        try {
            return $this->organization->downloadInvoice($invoiceId, [
                'vendor' => config('app.name'),
                'product' => 'Subscription',
            ]);
        } catch (\Exception $e) {
            $this->notification()->error('Error', 'Failed to download invoice: '.$e->getMessage());
        }
    }

    public function getCurrentPlan()
    {
        if (! $this->organization || ! $this->organization->subscribed('default')) {
            return 'free';
        }

        $subscription = $this->organization->subscription('default');
        $stripePlan = $subscription->stripe_price;

        // Match Stripe price ID to plan
        foreach ($this->plans as $key => $plan) {
            if (isset($plan['price_id']) && $plan['price_id'] === $stripePlan) {
                return $key;
            }
        }

        return 'free';
    }

    public function isCurrentPlan($plan)
    {
        return $this->getCurrentPlan() === $plan;
    }

    public function render()
    {
        return view('livewire.billing-manager', [
            'currentPlan' => $this->getCurrentPlan(),
        ]);
    }
}
