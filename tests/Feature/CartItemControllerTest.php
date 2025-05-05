<?php

namespace Tests\Feature;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate for all tests
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_routes()
    {
        // Attempt to access the index route without authentication
        $response = $this->getJson(route('cartItems.index'));

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.',
                 ]);
    }

    public function test_store_creates_cart_item_in_existing_pending_cart()
    {
        $this->actingAs($this->user, 'sanctum');

        // Create a pending cart for the user
        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
            'status' => CartStatus::Pending->value,
        ]);

        $product = Product::factory()->create();

        // Data for the cart item
        $data = [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50.00,
        ];

        // Store the cart item
        $response = $this->postJson(route('cartItems.store'), $data);

        $response->assertStatus(201)
                 ->assertJsonPath('data.cart_id', $cart->id)
                 ->assertJsonPath('data.product_id', $product->id)
                 ->assertJsonPath('data.quantity', 2)
                 ->assertJsonPath('data.price', '50.00');

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50.00,
        ]);
    }

    public function test_store_creates_new_cart_if_no_pending_cart_exists()
    {
        $this->actingAs($this->user, 'sanctum');

        // Ensure no pending cart exists for the user
        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
            'status' => CartStatus::Pending->value,
        ]);

        $product = Product::factory()->create();

        // Data for the cart item
        $data = [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50.00,
        ];

        // Store the cart item
        $response = $this->postJson(route('cartItems.store'), $data);

        $response->assertStatus(201)
                 ->assertJsonPath('data.product_id', $product->id)
                 ->assertJsonPath('data.quantity', 2)
                 ->assertJsonPath('data.price', '50.00');

        // Assert a new pending cart was created
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'status' => CartStatus::Pending->value,
        ]);

        // Assert the cart item was added to the new cart
        $cart = Cart::where('user_id', $this->user->id)
                    ->where('status', CartStatus::Pending->value)
                    ->first();

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50.00,
        ]);
    }

    public function test_index_returns_all_cart_items()
    {
        $this->actingAs($this->user, 'sanctum');

        $cart1 = Cart::factory()->create(['user_id' => $this->user->id]);
        $cart2 = Cart::factory()->create(['user_id' => $this->user->id]);

        CartItem::factory()->count(2)->create(['cart_id' => $cart1->id]);
        CartItem::factory()->count(3)->create(['cart_id' => $cart2->id]);

        $response = $this->getJson(route('cartItems.index'));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'cart_id',
                         'items' => [
                             '*' => [
                                 'id',
                                 'cart_id',
                                 'product_id',
                                 'quantity',
                                 'price',
                                 'total_sum',
                                 'created_at',
                                 'updated_at',
                             ],
                         ],
                     ],
                 ]);
    }

    public function test_show_returns_a_specific_cart_item()
    {
        $this->actingAs($this->user, 'sanctum');

        // Create a cart item
        $cartItem = CartItem::factory()->create();

        // Fetch the cart item
        $response = $this->getJson(route('cartItems.show', $cartItem));

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $cartItem->id)
                 ->assertJsonPath('data.cart_id', $cartItem->cart_id)
                 ->assertJsonPath('data.product_id', $cartItem->product_id);
    }

    public function test_update_modifies_a_cart_item()
    {
        $this->actingAs($this->user, 'sanctum');

        // Create a cart item
        $cartItem = CartItem::factory()->create([
            'quantity' => 1,
            'price' => 50.00,
        ]);

        // Data to update
        $data = [
            'quantity' => 3,
            'price' => 60.00,
        ];

        // Update the cart item
        $response = $this->putJson(route('cartItems.update', $cartItem), $data);

        $response->assertStatus(200)
                 ->assertJsonPath('data.quantity', 3)
                 ->assertJsonPath('data.price', '60.00');

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
            'price' => 60.00,
        ]);
    }

    public function test_destroy_deletes_a_cart_item()
    {
        $this->actingAs($this->user, 'sanctum');

        // Create a cart item
        $cartItem = CartItem::factory()->create();

        // Delete the cart item
        $response = $this->deleteJson(route('cartItems.destroy', $cartItem));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }
}