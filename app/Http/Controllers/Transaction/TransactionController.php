<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Transaction\StoreTransactionRequest;
use App\Http\Requests\Api\Transaction\UpdateTransactionStatusRequest;
use App\Services\Transaction\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }


    public function store(StoreTransactionRequest $request)
    {

        return $this->transactionService->create($request->all());
    }

    public function updateStatus(UpdateTransactionStatusRequest $request){

        return $this->transactionService->updateStatus($request->all());
    }
}
