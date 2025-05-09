<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
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
        // Test index route
        $response = $this->getJson(route('products.index'));
        $response->assertStatus(401);

        // Test store route
        $response = $this->postJson(route('products.store'), []);
        $response->assertStatus(401);

        // Test show route
        $product = Product::factory()->create();
        $response = $this->getJson(route('products.show', $product));
        $response->assertStatus(401);

        // Test update route
        $response = $this->putJson(route('products.update', $product), []);
        $response->assertStatus(401);

        // Test destroy route
        $response = $this->deleteJson(route('products.destroy', $product));
        $response->assertStatus(401);
    }

    public function test_index_returns_paginated_products()
    {
        $this->actingAs($this->user, 'sanctum');

        Product::factory()->count(15)->create();

        $response = $this->getJson(route('products.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_store_creates_a_new_product()
    {
        $this->actingAs($this->user, 'sanctum');

        $category = Category::factory()->create();

        $data = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
        ];

        $response = $this->postJson(route('products.store'), $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function test_show_returns_a_specific_product()
    {
        $this->actingAs($this->user, 'sanctum');

        $product = Product::factory()->create();

        $response = $this->getJson(route('products.show', $product));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_update_modifies_a_product()
    {
        $this->actingAs($this->user, 'sanctum');

        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Product',
            'description' => 'This is an updated product.',
            'price' => 199.99,
        ];

        $response = $this->putJson(route('products.update', $product), $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function test_destroy_deletes_a_product()
    {
        $this->actingAs($this->user, 'sanctum');

        $product = Product::factory()->create();

        $response = $this->deleteJson(route('products.destroy', $product));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
