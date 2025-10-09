<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $plan = null): Response
    {
        $user = $request->user();

        // If no user, let auth middleware handle it
        if (! $user) {
            return $next($request);
        }

        // Check if user's organization has an active subscription
        $organization = $user->primaryOrganization();
        if (! $organization || ! $organization->subscribed('default')) {
            return redirect()
                ->route('billing.index')
                ->with('error', 'This feature requires an active subscription. Please subscribe to continue.');
        }

        // If a specific plan is required, check if user is on that plan or higher
        if ($plan) {
            $plans = config('plans.plans');
            $planHierarchy = ['free', 'basic', 'pro', 'enterprise'];

            $requiredPlanLevel = array_search($plan, $planHierarchy);
            $currentPlan = $this->getUserPlan($user);
            $currentPlanLevel = array_search($currentPlan, $planHierarchy);

            if ($currentPlanLevel === false || $currentPlanLevel < $requiredPlanLevel) {
                return redirect()
                    ->route('billing.index')
                    ->with('error', 'This feature requires a '.ucfirst($plan).' plan or higher.');
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
