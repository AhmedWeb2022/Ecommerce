<?php

namespace App\Services\Order;

use App\Traits\ApiResponseTrait;
use App\Params\Order\OrderParam;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepository;

class OrderService
{
    use ApiResponseTrait;

    protected $orderParam;
    protected $orderRepository;

    public function __construct(OrderParam $orderParam, OrderRepository $orderRepository)
    {
        $this->orderParam = $orderParam;
        $this->orderRepository = $orderRepository;
    }

    public function getAll()
    {
        $response = $this->orderRepository->getAll();
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(OrderResource::collection($response['data']), 'Orders retrieved successfully');
    }

    public function findById(int $id)
    {
        $response = $this->orderRepository->findById($id);
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(new OrderResource($response['data']), $response['message']);
    }

    public function create(array $data)
    {
        $data['total_amount'] = calculateTotalAmount($data['orders']);
        // dd($data);
        $data['user_id'] = auth('api')->user()->id;
        $orderParam = $this->orderParam->setParams($data);

        $response = $this->orderRepository->create($orderParam->toArray());
        // dd($response);
        $order_products = [];
        foreach ($data['orders'] as $product) {
            $order_products[$product['product_id']] = [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ];
        }
        $response['data']->products()->attach($order_products);

        if (!$response['status']) {
            return $this->error($response['message']);
        }

        return $this->success(new OrderResource($response['data']), $response['message']);
    }

    public function update(array $data, int $id)
    {
        $data['total_amount'] = calculateTotalAmount($data['orders']);
        // dd($data);
        $data['user_id'] = auth('api')->user()->id;
        $orderParam = $this->orderParam->setParams($data);
        $response = $this->orderRepository->update($id, $orderParam->toArray());
        $order_products = [];
        foreach ($data['orders'] as $product) {
            $order_products[$product['product_id']] = [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ];
        }
        $response['data']->products()->sync($order_products);
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(new OrderResource($response['data']), $response['message']);
    }

    public function delete(int $id)
    {
        $response = $this->orderRepository->delete($id);
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(null, $response['message']);
    }
}
