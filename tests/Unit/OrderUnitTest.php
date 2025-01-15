<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Illuminate\Database\Capsule\Manager as DB;

class OrderUnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set up the database connection
        $db = new DB();
        $db->addConnection([
            'driver' => 'mysql', // Use the appropriate driver
            'host' => '127.0.0.1',
            'database' => 'pay_sky_db', // Your test database name
            'username' => 'root',          // Your database username
            'password' => '',              // Your database password
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        $db->setAsGlobal();
        $db->bootEloquent();
    }

    /** @test */
    // public function it_can_create_an_order_with_products()
    // {
    //     // Create a user
    //     $user = User::factory()->create();

    //     // Manually create a product
    //     $product = Product::create([
    //         'name' => 'Test Product',
    //         'price' => 100.50,
    //     ]);

    //     // Prepare order data
    //     $data = [
    //         'orders' => [
    //             [
    //                 'product_id' => $product->id,
    //                 'quantity' => 2,
    //                 'price' => 100.50,
    //             ],
    //         ],
    //     ];

    //     // Calculate total amount
    //     $totalAmount = 0;
    //     foreach ($data['orders'] as $orderItem) {
    //         $totalAmount += $orderItem['quantity'] * $orderItem['price'];
    //     }

    //     // Create the order with the user_id
    //     $order = Order::create([
    //         'user_id' => $user->id, // Associate the order with the user
    //         'total_amount' => $totalAmount,
    //         'status' => 0, // Default status
    //     ]);

    //     // Attach products to the order
    //     $orderProducts = [];
    //     foreach ($data['orders'] as $orderItem) {
    //         $orderProducts[$orderItem['product_id']] = [
    //             'quantity' => $orderItem['quantity'],
    //             'price' => $orderItem['price'],
    //         ];
    //     }
    //     $order->products()->attach($orderProducts);

    //     // Verify the order was created
    //     $this->assertNotNull($order);
    //     $this->assertEquals(201.00, $order->total_amount); // 2 * 100.50 = 201.00
    //     $this->assertEquals(0, $order->status);

    //     // Verify the order products were attached
    //     $orderFromDb = Order::with('products')->find($order->id);
    //     $this->assertCount(1, $orderFromDb->products);
    //     $this->assertEquals($product->id, $orderFromDb->products->first()->id);
    //     $this->assertEquals(2, $orderFromDb->products->first()->pivot->quantity);
    //     $this->assertEquals(100.50, $orderFromDb->products->first()->pivot->price);

    //     // Verify the database records
    //     $this->assertDatabaseHas('orders', [
    //         'id' => $order->id,
    //         'user_id' => $user->id, // Verify the user_id is correct
    //         'total_amount' => 201.00,
    //         'status' => 0,
    //     ]);

    //     $this->assertDatabaseHas('order_products', [
    //         'order_id' => $order->id,
    //         'product_id' => $product->id,
    //         'quantity' => 2,
    //         'price' => 100.50,
    //     ]);
    // }

    /** @test */
    // public function it_can_fetch_all_orders()
    // {
    //     // Create a user
    //     $user = User::factory()->create();

    //     // Create orders with products
    //     $order1 = Order::create([
    //         'user_id' => $user->id,
    //         'total_amount' => 100.00,
    //         'status' => 0,
    //     ]);

    //     $order2 = Order::create([
    //         'user_id' => $user->id,
    //         'total_amount' => 200.00,
    //         'status' => 1,
    //     ]);

    //     // Attach products to orders
    //     $product1 = Product::create(['name' => 'Product 1', 'price' => 50.00]);
    //     $product2 = Product::create(['name' => 'Product 2', 'price' => 100.00]);

    //     $order1->products()->attach($product1->id, ['quantity' => 2, 'price' => 50.00]);
    //     $order2->products()->attach($product2->id, ['quantity' => 1, 'price' => 100.00]);

    //     // Fetch all orders
    //     $response = $this->actingAs($user, 'api')->getJson('/api/orders');

    //     // Debug the response
    //     // dd($response->json());

    //     // Assert the response
    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'status' => true,
    //             'message' => 'Orders retrieved successfully',
    //         ])
    //         ->assertJsonStructure([
    //             'data' => [
    //                 '*' => [
    //                     'id',
    //                     'total_amount',
    //                     'status',
    //                     'products' => [
    //                         '*' => [
    //                             'id',
    //                             'name',
    //                             'price',
    //                             'quantity',
    //                         ],
    //                     ],
    //                 ],
    //             ],
    //         ]);
    // }
    /** @test */
    public function it_can_fetch_order_details()
    {
        // Create a user
        $user = User::factory()->create();

        // Create an order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 100.00,
            'status' => 0,
        ]);

        // Attach products to the order
        $product = Product::create(['name' => 'Test Product', 'price' => 50.00]);
        $order->products()->attach($product->id, ['quantity' => 2, 'price' => 50.00]);

        // Fetch order details
        $response = $this->actingAs($user, 'api')->getJson("/api/orders/{$order->id}");

        // Debug the response
        // dd($response->json());

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Order retrieved successfully',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_amount',
                    'status',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'quantity',
                        ],
                    ],
                ],
            ]);

        // Verify the order details
        $responseData = $response->json('data');
        $this->assertEquals($order->id, $responseData['id']);
        $this->assertEquals($product->id, $responseData['products'][0]['id']);
        $this->assertEquals(2, $responseData['products'][0]['quantity']);
    }
}
