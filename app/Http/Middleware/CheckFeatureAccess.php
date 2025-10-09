<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    /**
     * Handle an incoming request.
     *
     * Check if user has access to a specific feature based on their subscription plan
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        // If no user, let auth middleware handle it
        if (! $user) {
            return $next($request);
        }

        // Get current user plan
        $currentPlan = $this->getUserPlan($user);

        // Get features configuration
        $featureConfig = config('plans.features');

        // Check if feature requires subscription
        if (isset($featureConfig[$feature])) {
            $allowedPlans = $featureConfig[$feature];

            if (! in_array($currentPlan, $allowedPlans)) {
                // Find minimum required plan
                $planHierarchy = ['free', 'basic', 'pro', 'enterprise'];
                $minPlan = 'pro'; // default

                foreach ($planHierarchy as $plan) {
                    if (in_array($plan, $allowedPlans)) {
                        $minPlan = $plan;
                        break;
                    }
                }

                return redirect()
                    ->route('billing.index')
                    ->with('error', 'This feature requires a '.ucfirst($minPlan).' plan or higher. Please upgrade your subscription.');
            }
        }

        return $next($request);
    }

    /**
     * Get the current plan for the user's organization
     */
    protected function getUserPlan($user): string
    {
        $organization = $user->primaryOrganization();

        if (! $organization || ! $organization->subscribed('default')) {
            return 'free';
        }

        $subscription = $organization->subscription('default');
        $stripePlan = $subscription->stripe_price;

        // Match Stripe price ID to plan
        $plans = config('plans.plans');
        foreach ($plans as $key => $plan) {
            if (isset($plan['price_id']) && $plan['price_id'] === $stripePlan) {
                return $key;
            }
        }

        return 'free';
    }
}
