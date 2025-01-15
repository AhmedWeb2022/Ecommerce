<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ammount' => $this->ammount,
            'status' => $this->status,
            'user'=> new UserResource($this->user),
            'order'=> new OrderResource($this->order),
            'product'=> new ProductResource($this->product)
        ];
    }
}
