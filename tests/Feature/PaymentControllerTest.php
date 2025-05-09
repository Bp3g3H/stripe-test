<?php

namespace Tests\Feature;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate for all tests
        $this->user = User::factory()->create();
    }

    public function test_pay_returns_checkout_url()
    {
        // Simulate an authenticated user
        $this->actingAs($this->user, 'sanctum');

        // Create real data in the database
        $product = Product::factory()->create(['name' => 'Test Product', 'price' => 10]);
        $cart = Cart::factory()->create(['user_id' => $this->user->id, 'status' => CartStatus::Pending]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
            'total_sum' => $product->price,
        ]);
        // Call the pay action
        $response = $this->postJson(route('payment.pay'));

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJsonStructure(['checkout_url']);
    }
}
