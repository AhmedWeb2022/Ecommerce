<?php

namespace App\Repositories\Product;

use Exception;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Repository\RepositoryInterface;
use App\Traits\RepositoryResponseTrait;

class ProductRepository implements RepositoryInterface
{
    use RepositoryResponseTrait;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Retrieve all products from the database.
     *
     * @return array<string, mixed> An associative array containing the status, message, and data of products.
     */
    public function getAll(): array
    {
        try {
            $products = $this->product->all();
            return $this->successResponse($products, 'Products retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving all products: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving all products');
        }
    }

    /**
     * Find a product by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function findById($id): array
    {
        try {
            $product = $this->product->find($id);

            if (!$product) {
                return $this->notFoundResponse('Product not found');
            }

            return $this->successResponse($product, 'Product retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error finding product by ID: ' . $e->getMessage());
            return $this->errorResponse('Error finding product');
        }
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        DB::beginTransaction();

        try {
            $product = $this->product->create($data);
            DB::commit();
            return $this->successResponse($product, 'Product created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage());
            return $this->errorResponse('Unable to create product');
        }
    }

    /**
     * Update a product by ID.
     *
     * @param int $id
     * @param array $data
     * @return array<string, mixed>
     */
    public function update($id, array $data): array
    {
        DB::beginTransaction();

        try {
            $product = $this->product->find($id);

            if (!$product) {
                return $this->notFoundResponse('Product not found');
            }

            $product->update($data);
            DB::commit();
            return $this->successResponse($product, 'Product updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());
            return $this->errorResponse('Unable to update product');
        }
    }

    /**
     * Delete a product by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function delete($id): array
    {
        DB::beginTransaction();

        try {
            $product = $this->product->find($id);

            if (!$product) {
                return $this->notFoundResponse('Product not found');
            }

            $product->delete();
            DB::commit();
            return $this->successResponse(null, 'Product deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product: ' . $e->getMessage());
            return $this->errorResponse('Unable to delete product');
        }
    }
}
