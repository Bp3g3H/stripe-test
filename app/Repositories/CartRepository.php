<?php

namespace App\Repositories;

use App\Enums\CartStatus;
use App\Models\Cart;

class CartRepository
{
    public function getCartWithStatusPending(int $userId): ?Cart
    {
        return Cart::with('items.product') // Load the CartItem relationship
            ->where('user_id', $userId)
            ->where('status', CartStatus::Pending->value)
            ->first();
    }
}