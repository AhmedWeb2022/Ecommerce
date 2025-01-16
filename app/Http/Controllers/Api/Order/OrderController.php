<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Requests\Api\Order\UpdateOrderRequest;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return $this->orderService->getAll();
    }

    public function show($id)
    {
        return $this->orderService->findById($id);
    }

    public function store(StoreOrderRequest $request)
    {
        return $this->orderService->create($request->all());
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        return $this->orderService->update($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->orderService->delete($id);
    }

}
