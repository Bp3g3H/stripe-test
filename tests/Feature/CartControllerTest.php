<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate for all tests
        $this->user = User::factory()->create();
    }

    public function test_can_list_all_carts()
    {
        $this->actingAs($this->user, 'sanctum');

        Cart::factory()->count(3)->create();

        $response = $this->getJson(route('carts.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'status',
                        'total_sum',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_can_create_a_new_cart()
    {
        $this->actingAs($this->user, 'sanctum');

        $data = [
            'status' => 'pending',
        ];

        $response = $this->postJson(route('carts.store'), $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('carts', $data);
    }

    public function test_can_show_a_specific_cart()
    {
        $this->actingAs($this->user, 'sanctum');

        $cart = Cart::factory()->create();

        $response = $this->getJson(route('carts.show', $cart));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $cart->id)
            ->assertJsonPath('data.status', $cart->status);
    }

    public function test_can_update_a_cart()
    {
        $this->actingAs($this->user, 'sanctum');

        $cart = Cart::factory()->create([
            'status' => 'pending',
        ]);

        $data = [
            'status' => 'completed',
        ];

        $response = $this->putJson(route('carts.update', $cart), $data);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'completed');

        $this->assertDatabaseHas('carts', $data);
    }

    public function test_can_delete_a_cart()
    {
        $this->actingAs($this->user, 'sanctum');

        $cart = Cart::factory()->create();

        $response = $this->deleteJson(route('carts.destroy', $cart));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_unauthenticated_user_cannot_access_routes()
    {
        // Attempt to access the index route without authentication
        $response = $this->getJson(route('carts.index'));

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
