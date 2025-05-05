<?php

namespace App\Http\Controllers;

use App\Enums\CartStatus;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemCollection;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = CartItem::with('cart')->get(); // Fetch all cart items with their related cart

        return new CartItemCollection($cartItems); // Pass the flat collection to the resource
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartItemRequest $request)
    {
         // Get the authenticated user
        $user = Auth::user();

        // Check if the user has a pending cart
        $cart = $user->carts()->where('status', CartStatus::Pending)->first();

        // If no pending cart exists, create one
        if (!$cart) {
            $cart = Cart::createPending($user->id);
        }

        // Add the cart item to the cart
        $cartItem = $cart->items()->create($request->validated());
        return new CartItemResource($cartItem);
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        return new CartItemResource($cartItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
         // Update the cart item with validated data
        $cartItem->update($request->validated());

        return new CartItemResource($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        // Delete the cart item
        return $cartItem->delete();
    }
}
