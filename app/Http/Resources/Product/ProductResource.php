<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    protected $orderId;

    public function __construct($resource, $orderId = null)
    {
        parent::__construct($resource);
        $this->orderId = $orderId;
    }

    public function toArray(Request $request): array
    {
        $order = $this->orders()->where('order_id', $this->orderId)->first();
        $resource = [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'price' => $this->price ?? '',
        ];

        if ($order) {
            $resource['quantity'] = $order->pivot->quantity;
        }
        return $resource;
    }
}
