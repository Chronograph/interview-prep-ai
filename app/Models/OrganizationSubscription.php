<?php

namespace App\Models;

use Laravel\Cashier\Subscription;

class OrganizationSubscription extends Subscription
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';

    /**
     * The foreign key for the organization relationship.
     *
     * @var string
     */
    protected $foreignKey = 'organization_id';

    /**
     * Get the organization that owns the subscription.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
