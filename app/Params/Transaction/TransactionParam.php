<?php


namespace App\Params\Transaction;

use App\Enums\TransactionStatusEnum;

class TransactionParam
{
    public $user_id;
    public $product_id;
    public $order_id;
    public $ammount;
    public $status;


    public function __construct(array $data = [])
    {
        $this->user_id = $data['user_id'] ?? null;
        $this->product_id = $data['product_id'] ?? null;
        $this->order_id = $data['order_id'] ?? null;
        $this->ammount = $data['ammount'] ?? 0;
        $this->status = $data['status'] ?? TransactionStatusEnum::PENDING->value;
    }

    public function setParams(array $data)
    {
        $this->user_id = $data['user_id'] ?? null;
        $this->product_id = $data['product_id'] ?? null;
        $this->order_id = $data['order_id'] ?? null;
        $this->ammount = $data['ammount'] ?? 0;
        $this->status = $data['status'] ?? TransactionStatusEnum::PENDING->value;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'user_id' => $this->user_id,
                'product_id' => $this->product_id,
                'order_id' => $this->order_id,
                'ammount' => $this->ammount,
                'status' => $this->status
            ],
            fn($value) => $value !== null
        );
    }
}
