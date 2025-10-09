<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle subscription created webhook
     */
    protected function handleCustomerSubscriptionCreated(array $payload)
    {
        // Custom logic when subscription is created
        \Log::info('Subscription created', $payload);

        return parent::handleCustomerSubscriptionCreated($payload);
    }

    /**
     * Handle subscription updated webhook
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        // Custom logic when subscription is updated
        \Log::info('Subscription updated', $payload);

        return parent::handleCustomerSubscriptionUpdated($payload);
    }

    /**
     * Handle subscription deleted webhook
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        // Custom logic when subscription is deleted/cancelled
        \Log::info('Subscription deleted', $payload);

        return parent::handleCustomerSubscriptionDeleted($payload);
    }

    /**
     * Handle payment succeeded webhook
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        // Custom logic when payment succeeds
        \Log::info('Payment succeeded', $payload);

        return parent::handleInvoicePaymentSucceeded($payload);
    }

    /**
     * Handle payment failed webhook
     */
    protected function handleInvoicePaymentFailed(array $payload)
    {
        // Custom logic when payment fails
        \Log::info('Payment failed', $payload);

        // You could send a notification to the user here

        return parent::handleInvoicePaymentFailed($payload);
    }
}
