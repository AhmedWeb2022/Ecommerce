<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderFeatureTest extends TestCase
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
    public function it_can_retrieve_all_orders()
    {
        // Manually create 5 orders
        for ($i = 0; $i < 5; $i++) {
            Order::create([
                'total_amount' => 100.00 + $i,
                'status' => 0,
                'user_id' => $this->user->id
            ]);
        }

        $response = $this->actingAs($this->user, 'api')->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Orders retrieved successfully',
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'total_amount', 'status']
                ]
            ]);
    }

    /** @test */
    public function it_can_retrieve_a_single_order()
    {
        // Manually create an order
        $order = Order::create([
            'total_amount' => 200.00,
            'status' => 1,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'api')->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Order retrieved successfully',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'total_amount', 'status'],
            ]);
    }

    /** @test */
    public function it_can_create_an_order()
    {
        // Manually create a product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 100.50,
        ]);

        $data = [
            'orders' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 100.50,
                ],
            ],
        ];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/orders', $data);
        // dd($response);
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Order created successfully',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'total_amount', 'status'],
            ]);

        // Check if the order was created in the database
        $this->assertDatabaseHas('orders', [
            'total_amount' => 221.1, // 2 * 100.50 = 201.00
            'status' => 0, // Default status
        ]);

        // Check if the order products were attached
        $order = Order::first();
        $this->assertDatabaseHas('order_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100.50,
        ]);
    }

    /** @test */
    // public function it_can_update_an_order()
    // {
    //     // Manually create an order
    //     $order = Order::create([
    //         'total_amount' => 200.00,
    //         'status' => 0,
    //     ]);

    //     // Manually create a product
    //     $product = Product::create([
    //         'name' => 'Test Product',
    //         'price' => 150.75,
    //     ]);

    //     $data = [
    //         'orders' => [
    //             [
    //                 'product_id' => $product->id,
    //                 'quantity' => 3,
    //                 'price' => 150.75,
    //             ],
    //         ],
    //     ];

    //     $response = $this->actingAs($this->user, 'api')->putJson("/api/orders/{$order->id}", $data);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'status' => true,
    //             'message' => 'Order updated successfully',
    //         ])
    //         ->assertJsonStructure([
    //             'data' => ['id', 'total_amount', 'status', 'created_at', 'updated_at'],
    //         ]);

    //     // Check if the order was updated in the database
    //     $this->assertDatabaseHas('orders', [
    //         'id' => $order->id,
    //         'total_amount' => 452.25, // 3 * 150.75 = 452.25
    //     ]);

    //     // Check if the order products were updated
    //     $this->assertDatabaseHas('order_product', [
    //         'order_id' => $order->id,
    //         'product_id' => $product->id,
    //         'quantity' => 3,
    //         'price' => 150.75,
    //     ]);
    // }

    /** @test */
    // public function it_can_delete_an_order()
    // {
    //     // Manually create an order
    //     $order = Order::create([
    //         'total_amount' => 200.00,
    //         'status' => 0,
    //     ]);

    //     $response = $this->actingAs($this->user, 'api')->deleteJson("/api/orders/{$order->id}");

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'status' => true,
    //             'message' => 'Order deleted successfully',
    //         ]);

    //     // Check if the order was soft-deleted
    //     $this->assertSoftDeleted('orders', [
    //         'id' => $order->id,
    //     ]);
    // }
}
