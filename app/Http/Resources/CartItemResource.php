<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => number_format($this->price, 2), // Ensure price is always formatted as 2 decimal places
            'total_sum' => number_format($this->total_sum, 2),
            'user' => new UserResource($this->whenLoaded('cart.user')), // Include user via cart relationship
            'product' => new ProductResource($this->whenLoaded('product')), // Include product relationship
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
