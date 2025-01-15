<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderUnitTest extends TestCase
{
    use RefreshDatabase; // Automatically migrates and resets the database

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an authenticated user for testing
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // Use Hash facade
        ]);
    }

    /** @test */
    public function test_create_order()
    {
        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 100.50,
        ]);

        // Prepare order data
        $data = [
            [
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 100.50,
            ],
        ];

        // Calculate total amount using the helper function
        $totalAmount = $this->calculateTotalAmount($data);

        // Create the order
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_amount' => $totalAmount,
            'status' => 0,
        ]);

        // Attach products to the order
        $orderProducts = [];
        foreach ($data as $orderItem) {
            $orderProducts[$orderItem['product_id']] = [
                'quantity' => $orderItem['quantity'],
                'price' => $orderItem['price'],
            ];
        }
        $order->products()->attach($orderProducts);

        // Verify the order was created
        $this->assertNotNull($order);
        $this->assertEquals($this->user->id, $order->user_id);
        $this->assertEquals(221.10, $order->total_amount); // 2 * 100.50 = 201.00 + 10% tax = 221.10
        $this->assertEquals(0, $order->status);

        // Verify the order products were attached
        $orderFromDb = Order::with('products')->find($order->id);
        $this->assertCount(1, $orderFromDb->products);
        $this->assertEquals($product->id, $orderFromDb->products->first()->id);
        $this->assertEquals(2, $orderFromDb->products->first()->pivot->quantity);
        $this->assertEquals(100.50, $orderFromDb->products->first()->pivot->price);

        // Verify the database records
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $this->user->id,
            'total_amount' => 221.10,
            'status' => 0,
        ]);

        $this->assertDatabaseHas('order_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100.50,
        ]);
    }

    /** @test */
    public function test_read_order()
    {


        // Create an order
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_amount' => 200.00,
            'status' => 1,
        ]);

        // Fetch the order from the database
        $orderFromDb = Order::find($order->id);

        // Verify the order exists
        $this->assertNotNull($orderFromDb);
        $this->assertEquals($this->user->id, $orderFromDb->user_id);
        $this->assertEquals(200.00, $orderFromDb->total_amount);
        $this->assertEquals(1, $orderFromDb->status);
    }

    /** @test */
    public function test_update_order()
    {


        // Create an order
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_amount' => 200.00,
            'status' => 0,
        ]);

        // Update the order
        $order->update([
            'total_amount' => 300.00,
            'status' => 1,
        ]);

        // Fetch the updated order
        $orderFromDb = Order::find($order->id);

        // Verify the order was updated
        $this->assertEquals(300.00, $orderFromDb->total_amount);
        $this->assertEquals(1, $orderFromDb->status);
    }

    /** @test */
    public function test_delete_order()
    {


        // Create an order
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_amount' => 200.00,
            'status' => 0,
        ]);

        // Delete the order
        $order->delete();

        // Verify the order no longer exists
        $orderFromDb = Order::find($order->id);
        $this->assertNull($orderFromDb);
    }

    /**
     * Helper function to calculate total amount with tax.
     *
     * @param array $data
     * @return float
     */
    protected function calculateTotalAmount(array $data): float
    {
        $totalAmount = 0;
        foreach ($data as $value) {
            $subtotal = $value['quantity'] * $value['price'];
            $tax = $subtotal * 0.10; // 10% tax
            $totalAmount += $subtotal + $tax;
        }

        return $totalAmount;
    }
}
