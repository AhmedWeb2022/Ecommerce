<?php

namespace App\Repositories\Order;

use Exception;
use App\Models\Order\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Repository\RepositoryInterface;
use App\Traits\RepositoryResponseTrait;

class OrderRepository implements RepositoryInterface
{
    use RepositoryResponseTrait;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Retrieve all orders from the database.
     *
     * @return array<string, mixed> An associative array containing the status, message, and data of orders.
     */
    public function getAll(): array
    {
        try {
            $orders = $this->order->all();
            return $this->successResponse($orders, 'Orders retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving all orders: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving all orders');
        }
    }

    /**
     * Find an order by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function findById($id): array
    {
        try {
            $order = $this->order->find($id);

            if (!$order) {
                return $this->notFoundResponse('Order not found');
            }

            return $this->successResponse($order, 'Order retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error finding order by ID: ' . $e->getMessage());
            return $this->errorResponse('Error finding order');
        }
    }

    /**
     * Create a new order.
     *
     * @param array $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        DB::beginTransaction();

        try {
            // dd($data);
            $order = $this->order->create($data);
            DB::commit();
            return $this->successResponse($order, 'Order created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            return $this->errorResponse('Unable to create order');
        }
    }

    /**
     * Update an order by ID.
     *
     * @param int $id
     * @param array $data
     * @return array<string, mixed>
     */
    public function update($id, array $data): array
    {
        DB::beginTransaction();

        try {
            $order = $this->order->find($id);


            if (!$order) {
                return $this->notFoundResponse('Order not found');
            }

            $order->update($data);
            $order->products()->sync([$data['product_id'] => [
                'quantity' => $data['quantity'],
                'price' => $data['price'],
            ]]);
            DB::commit();
            return $this->successResponse($order, 'Order updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage());
            return $this->errorResponse('Unable to update order');
        }
    }

    /**
     * Delete an order by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function delete($id): array
    {
        DB::beginTransaction();

        try {
            $order = $this->order->find($id);

            if (!$order) {
                return $this->notFoundResponse('Order not found');
            }

            $orderProducts = $order->orderProducts;
            foreach ($orderProducts as $orderProduct) {
                $orderProduct->delete();
            }
            $order->delete();
            DB::commit();
            return $this->successResponse(null, 'Order deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());
            return $this->errorResponse('Unable to delete order');
        }
    }
}
