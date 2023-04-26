<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_can_list_categories()
    {
        $categories = Category::factory(10)->create();

        $response = $this->getJson('/api/category?limit=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_category()
    {
        $data = [
            'name' => $this->faker->unique()->word,
            'is_active' => $this->faker->boolean,
        ];

        $response = $this->postJson('/api/category', $data);

        $response->assertStatus(201);
    }

    public function test_can_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/category/$category->id");

        $response->assertStatus(200)
        ->assertJson(['data' => $category->toArray()]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => $this->faker->unique()->word,
            'is_active' => $this->faker->boolean,
        ];

        $response = $this->putJson("/api/category/$category->id", $data);

        $response->assertStatus(200);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/category/$category->id");

        $response->assertStatus(204);

        $this->assertNull(Category::find($category->id));
    }
}
