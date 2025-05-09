<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\Payable;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePayment implements Payable
{
    private array $billingItems = [];

    /**
     * Class constructor.
     */
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY', 'your-stripe-secret-key'));
    }

    public function set(array $billingItems)
    {
        $this->billingItems = $billingItems;
    }

    public function pay($identifier)
    {
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $this->billingItems,
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'identifier' => $identifier, // Pass the cart ID securely
            ],
        ]);

        return $checkoutSession->url;
    }
}
