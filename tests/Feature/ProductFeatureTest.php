<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an authenticated user for API requests
        $this->user = User::factory()->create();
    }

    /** @test */
    /** @test */
    public function it_can_retrieve_all_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->actingAs($this->user, 'api')->getJson('/api/products');

        // Debugging: Inspect the response
        // dd($response->json());

        $response->assertStatus(200)
            ->assertJson([
                'status' => true, // Check for this key explicitly
                'message' => 'Products retrieved successfully',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price']
                ]
            ]);
    }

    /** @test */
    public function it_can_retrieve_a_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user, 'api')->getJson("/api/products/{$product->id}");
        // dd($response->getContent());
        $response->assertStatus(200)
            ->assertJson([
                'status' => true, // Check for this key explicitly
                'message' => 'Product retrieved successfully',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'price'],
            ]);
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100.50
        ];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/products', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true, // Check for this key explicitly
                'message' => 'Product created successfully', // Correct message for creating
            ])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'price'], // Ensure you also check for timestamps
            ]);

        $this->assertDatabaseHas('products', $data);
    }


    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create();
        $data = [
            'name' => 'Updated Product',
            'price' => 200.75
        ];

        $response = $this->actingAs($this->user, 'api')->putJson("/api/products/{$product->id}", $data);

        // dd($response->json()); // Uncomment to inspect the actual response

        $response->assertStatus(200)
            ->assertJson([
                'status' => true, // Check for this key explicitly
                'message' => 'Product updated successfully', // Adjust this message if needed
            ])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'price', 'created_at', 'updated_at'], // Ensure you also check for timestamps
            ]);

        // Check if the database was updated correctly
        $this->assertDatabaseHas('products', $data);
    }


    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user, 'api')->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Product deleted successfully'
            ]);

        // If using soft deletes, check that the product has a 'deleted_at' field
        // $this->assertDatabaseMissing('products', ['id' => $product->id]);

        // Alternatively, for soft deletes:
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => now()
        ]);
    }
}
