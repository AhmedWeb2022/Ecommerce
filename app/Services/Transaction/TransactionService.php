<?php

namespace App\Services\Transaction;

use App\Traits\ApiResponseTrait;
use App\Params\Transaction\TransactionParam;
use App\Http\Resources\Transaction\TransactionResource;
use App\Repositories\Transaction\TransactionRepository;

class TransactionService
{
    use ApiResponseTrait;

    protected $transactionParam;
    protected $transactionRepository;

    public function __construct(TransactionParam $transactionParam, TransactionRepository $transactionRepository)
    {
        $this->transactionParam = $transactionParam;
        $this->transactionRepository = $transactionRepository;
    }

    public function getAll()
    {
        $response = $this->transactionRepository->getAll();
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(TransactionResource::collection($response['data']), 'Orders retrieved successfully');
    }

    public function findById(int $id)
    {
        $response = $this->transactionRepository->findById($id);
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(new TransactionResource($response['data']), $response['message']);
    }

    public function create(array $data)
    {
        // dd($data);
        $data['user_id'] = auth('api')->user()->id;
        $transactionParam = $this->transactionParam->setParams($data);
        // dd($transactionParam->toArray());
        $response = $this->transactionRepository->create($transactionParam->toArray());

        if (!$response['status']) {
            return $this->error($response['message']);
        }

        return $this->success(new TransactionResource($response['data']), $response['message']);
    }

    public function updateStatus(array $data){
        // dd($data);
        $transactionParam = $this->transactionParam->setParams($data);
        // dd($transactionParam->toArray());
        $response = $this->transactionRepository->update($data['id'], $transactionParam->toArray());
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(new TransactionResource($response['data']), $response['message']);
    }
}
