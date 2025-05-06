<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class CategoryControllerTest extends TestCase
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
        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.',
                 ]);
    }

    public function test_can_list_all_categories()
    {
        $this->actingAs($this->user, 'sanctum');

        Category::factory()->count(3)->create();

        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'created_at',
                             'updated_at',
                         ],
                     ],
                     'links',
                     'meta',
                 ]);
    }

    public function test_can_create_a_new_category()
    {
        $this->actingAs($this->user, 'sanctum');

        $data = [
            'name' => 'Category Name',
        ];

        $response = $this->postJson(route('categories.store'), $data);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', $data['name']);

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_can_show_a_specific_category()
    {
        $this->actingAs($this->user, 'sanctum');

        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.show', $category));
        
        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $category->id)
                 ->assertJsonPath('data.name', $category->name);
    }

    public function test_can_update_a_category()
    {
        $this->actingAs($this->user, 'sanctum');

        $category = Category::factory()->create([
            'name' => 'Category Name',
        ]);

        $data = [
            'name' => 'Updated Category Name',
        ];

        $response = $this->putJson(route('categories.update', $category), $data);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Updated Category Name');

        $this->assertDatabaseHas('categories', $data);
    }

    public function test_can_delete_a_category()
    {
        $this->actingAs($this->user, 'sanctum');

        $category = Category::factory()->create();

        $response = $this->deleteJson(route('categories.destroy', $category));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
