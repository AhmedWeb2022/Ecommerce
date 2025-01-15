<?php


namespace App\Params\Order;


class OrderParam
{
    public $total_amount;
    public $status;
    public $user_id;


    public function __construct(array $data = [])
    {
        $this->total_amount = $data['total_amount'] ?? null;
        $this->status = $data['status'] ?? 0;
        $this->user_id = $data['user_id'] ?? null;
    }

    public function setParams(array $data)
    {
        $this->total_amount = $data['total_amount'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->status = $data['status'] ?? 0;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'user_id' => $this->user_id
        ];
    }
}
