<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           // Fetch all products with pagination
           $cart = Cart::paginate(10);

           return new CartCollection($cart);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
          // Create a new product
          $cart = Cart::createPending(Auth::id(), $request->validated());

          return new CartResource($cart);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        return new CartResource($cart);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        // Update the cart with validated data
        $cart->update($request->validated());

        return  new CartResource($cart);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        return $cart->delete();
    }
}
