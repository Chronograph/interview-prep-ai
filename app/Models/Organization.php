<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;

class Organization extends Model
{
    use Billable, HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'owner_id',
        'logo_url',
        'website',
        'industry',
        'size',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'size' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($organization) {
            if (empty($organization->slug)) {
                $organization->slug = Str::slug($organization->name);

                // Ensure uniqueness
                $count = static::where('slug', 'like', $organization->slug.'%')->count();
                if ($count > 0) {
                    $organization->slug = $organization->slug.'-'.($count + 1);
                }
            }
        });
    }

    /**
     * Get the owner of the organization
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all members of the organization
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get all teams in the organization
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the organization's subscription (one per organization)
     */
    public function subscription($name = 'default')
    {
        return $this->hasOne(OrganizationSubscription::class, 'organization_id')->first();
    }

    /**
     * Get all subscriptions for the organization (for compatibility, but should only be one)
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(OrganizationSubscription::class, 'organization_id');
    }

    /**
     * Override the Billable trait's subscribed method to use organization_id
     */
    public function subscribed($name = 'default', $price = null)
    {
        $subscription = $this->subscription($name);

        if (! $subscription) {
            return false;
        }

        if ($subscription->ended()) {
            return false;
        }

        if ($price && $subscription->stripe_price !== $price) {
            return false;
        }

        return $subscription->valid();
    }

    /**
     * Create or update the organization's subscription (only one allowed)
     */
    public function createOrUpdateSubscription($stripeId, $stripeStatus, $stripePrice, $quantity = 1, $trialEndsAt = null, $endsAt = null)
    {
        // Cancel any existing subscriptions first
        $this->subscriptions()->update([
            'stripe_status' => 'canceled',
            'ends_at' => now(),
        ]);

        // Create new subscription
        return $this->subscriptions()->create([
            'name' => 'default',
            'stripe_id' => $stripeId,
            'stripe_status' => $stripeStatus,
            'stripe_price' => $stripePrice,
            'quantity' => $quantity,
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => $endsAt,
        ]);
    }

    /**
     * Check if a user is a member of the organization
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is the owner of the organization
     */
    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Get the role of a user in the organization
     */
    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->id)->first();

        return $member?->pivot->role;
    }

    /**
     * Check if user has a specific role or higher
     */
    public function hasRole(User $user, string $role): bool
    {
        if ($this->isOwner($user)) {
            return true; // Owner has all permissions
        }

        $userRole = $this->getMemberRole($user);
        if (! $userRole) {
            return false;
        }

        $hierarchy = ['member' => 1, 'admin' => 2, 'owner' => 3];
        $requiredLevel = $hierarchy[$role] ?? 0;
        $userLevel = $hierarchy[$userRole] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Check if organization can add more members based on subscription
     */
    public function canAddMoreMembers(): bool
    {
        if (! $this->subscribed('default')) {
            // Free plan
            $limit = config('plans.plans.free.limits.team_members', 0);
        } else {
            $currentPlan = $this->getCurrentPlan();
            $limit = config("plans.plans.{$currentPlan}.limits.team_members", 0);
        }

        // -1 means unlimited
        if ($limit === -1) {
            return true;
        }

        return $this->members()->count() < $limit;
    }

    /**
     * Get current subscription plan
     */
    public function getCurrentPlan(): string
    {
        if (! $this->subscribed('default')) {
            return 'free';
        }

        $subscription = $this->subscription('default');
        $stripePlan = $subscription->stripe_price;
        $plans = config('plans.plans');

        foreach ($plans as $key => $plan) {
            if (isset($plan['price_id']) && $plan['price_id'] === $stripePlan) {
                return $key;
            }
        }

        return 'free';
    }

    /**
     * Check if organization is on a specific plan
     */
    public function onPlan(string $plan): bool
    {
        if (! $this->subscribed('default')) {
            return $plan === 'free';
        }

        $subscription = $this->subscription('default');
        $stripePlan = $subscription->stripe_price;
        $plans = config('plans.plans');

        if (isset($plans[$plan]['price_id'])) {
            return $plans[$plan]['price_id'] === $stripePlan;
        }

        return false;
    }

    /**
     * Check if organization has access to a feature
     */
    public function canAccessFeature(string $feature): bool
    {
        $currentPlan = $this->getCurrentPlan();
        $featureConfig = config('plans.features');

        if (! isset($featureConfig[$feature])) {
            return true;
        }

        return in_array($currentPlan, $featureConfig[$feature]);
    }

    /**
     * Get plan limit for a specific resource
     */
    public function getPlanLimit(string $limitType): int
    {
        $currentPlan = $this->getCurrentPlan();
        $plans = config('plans.plans');

        if (isset($plans[$currentPlan]['limits'][$limitType])) {
            return $plans[$currentPlan]['limits'][$limitType];
        }

        return 0;
    }

    /**
     * Check if organization has reached a limit
     */
    public function hasReachedLimit(string $limitType, int $currentUsage): bool
    {
        $limit = $this->getPlanLimit($limitType);

        if ($limit === -1) {
            return false;
        }

        return $currentUsage >= $limit;
    }
}
