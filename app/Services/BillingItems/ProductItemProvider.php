<?php

namespace App\Services\BillingItems;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Services\BillingItems\Contracts\ItemProvider;

class ProductItemProvider implements ItemProvider
{
    private string $identifier;

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function get($user_id): array
    {
        // Query the cart with the 'pending' status for the given user
        $cart = Cart::with('items.product') // Load the CartItem relationship
            ->where('user_id', $user_id)
            ->where('status', CartStatus::Pending->value)
            ->first();

        // If no pending cart exists, return an empty array
        if (!$cart) {
            //! TODO LOG AND HANDLE THIS CASE
            return [];
        }

        // Set the identifier to the cart ID
        $this->setIdentifier($cart->id);

        $cartItems = $cart->items->map(function ($item) {
            return array_merge($item->toArray(), [
                'name' => $item->product->name ?? null,
            ]);
        });

        // Return the cart items as an array
        return $cartItems->toArray();
    }
}