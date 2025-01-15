<?php

namespace Tests\Unit;


use PHPUnit\Framework\TestCase;
use App\Models\Product\Product;
use Illuminate\Database\Capsule\Manager as DB;
use App\Repositories\Product\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUnitTest extends TestCase
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

    public function test_create_product()
    {
        // Create a new product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 100.00,
        ]);

        // Verify it exists in the database
        $this->assertNotNull($product);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(100.00, $product->price);
    }

    public function test_read_product()
    {
        // Insert a product directly
        $product = Product::create([
            'name' => 'Read',
            'price' => 200.00,
        ]);

        // Fetch the product from the database
        $productFromDb = Product::find($product->id);

        $this->assertNotNull($productFromDb);
        $this->assertEquals('Read', $productFromDb->name);
        $this->assertEquals(200.00, $productFromDb->price);
    }

    public function test_update_product()
    {
        // Create a product
        $product = Product::create([
            'name' => 'Product to Update',
            'price' => 300.00,
        ]);

        // Update the product
        $product->update([
            'name' => 'Updated Product',
            'price' => 400.00,
        ]);

        // Fetch the updated product
        $productFromDb = Product::find($product->id);

        $this->assertEquals('Updated Product', $productFromDb->name);
        $this->assertEquals(400.00, $productFromDb->price);
    }

    public function test_delete_product()
    {
        // Create a product
        $product = Product::create([
            'name' => 'Product to Delete',
            'price' => 500.00,
        ]);

        // Delete the product
        $product->delete();

        // Verify it's no longer in the database
        $productFromDb = Product::find($product->id);
        $this->assertNull($productFromDb);
    }
}
