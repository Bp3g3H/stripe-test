<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            // log
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // log
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            
            // Access metadata or session details
            $identifier = $session->metadata->identifier;
            FacadesLog::info('Stripe Webhook', [
                'identifier' => $identifier,
            ]);
            // Mark the cart as paid
            $cart = Cart::find($identifier);
            $cart->setCompleted();
        }
    }
}
