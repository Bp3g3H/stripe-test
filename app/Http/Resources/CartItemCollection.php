<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartItemCollection extends ResourceCollection
{
    public static $wrap = false;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->groupBy('cart_id')->map(function ($cartItems, $cartId) {
            return [
                'cart_id' => $cartId,
                'items' => CartItemResource::collection($cartItems),
            ];
        })->values()->all(); // Ensure the result is converted to a plain array
    }
}
