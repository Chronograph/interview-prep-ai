<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Create a Stripe Checkout session
     */
    public function checkout(Request $request, string $plan)
    {
        $user = Auth::user();
        $plans = config('plans.plans');
        $planConfig = $plans[$plan] ?? null;

        if (! $planConfig || $plan === 'free') {
            return redirect()->route('billing.index')->with('error', 'Invalid plan selected.');
        }

        $priceId = $planConfig['price_id'];

        if (! $priceId) {
            return redirect()->route('billing.index')->with('error', 'This plan is not properly configured.');
        }

        try {
            return $user->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('billing.success').'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('billing.index'),
                ]);
        } catch (\Exception $e) {
            return redirect()->route('billing.index')->with('error', 'Failed to create checkout session: '.$e->getMessage());
        }
    }

    /**
     * Handle successful subscription
     */
    public function success(Request $request)
    {
        return view('billing.success');
    }
}
