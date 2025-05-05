<?php

namespace App\Observers;

use App\Models\CartItem;

class CartItemObserver
{
    /**
     * Handle the CartItem "creating" event.
     */
    public function creating(CartItem $cartItem)
    {
        // Calculate total_sum before creating the record
        $cartItem->total_sum = $cartItem->quantity * $cartItem->price;
    }

    /**
     * Handle the CartItem "updating" event.
     */
    public function updating(CartItem $cartItem)
    {
        // Recalculate total_sum before updating the record
        $cartItem->total_sum = $cartItem->quantity * $cartItem->price;
    }
}
