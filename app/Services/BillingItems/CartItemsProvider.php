<?php

namespace App\Services\BillingItems;

use App\Repositories\CartRepository;
use App\Services\BillingItems\Contracts\ItemProvider;

class CartItemsProvider implements ItemProvider
{
    private ?string $identifier = null;
    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        // Initialize the CartRepository
        $this->cartRepository = $cartRepository;
    }

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
        $cart = $this->cartRepository->getCartWithStatusPending($user_id);
        //dd($cart->id);
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