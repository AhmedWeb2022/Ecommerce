<?php

namespace App\Repositories\Transaction;

use Exception;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Models\Transaction\Transaction;
use App\Traits\RepositoryResponseTrait;
use App\Interfaces\Repository\RepositoryInterface;

class TransactionRepository implements RepositoryInterface
{
    use RepositoryResponseTrait;

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Retrieve all orders from the database.
     *
     * @return array<string, mixed> An associative array containing the status, message, and data of orders.
     */
    public function getAll(): array
    {
        try {
            $transactions = $this->transaction->all();
            return $this->successResponse($transactions, 'Transactions retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving all transactions: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving all transactions');
        }
    }

    /**
     * Find an transaction by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function findById($id): array
    {
        try {
            $transaction = $this->transaction->find($id);

            if (!$transaction) {
                return $this->notFoundResponse('transaction not found');
            }

            return $this->successResponse($transaction, 'transaction retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error finding transaction by ID: ' . $e->getMessage());
            return $this->errorResponse('Error finding transaction');
        }
    }

    /**
     * Create a new transaction.
     *
     * @param array $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        DB::beginTransaction();

        try {
            // dd($data);
            $transaction = $this->transaction->create($data);
            DB::commit();
            return $this->successResponse($transaction, 'transaction created successfully');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error('Error creating transaction: ' . $e->getMessage());
            return $this->errorResponse('Unable to create transaction');
        }
    }

    /**
     * Update an transaction by ID.
     *
     * @param int $id
     * @param array $data
     * @return array<string, mixed>
     */
    public function update($id, array $data): array
    {
        DB::beginTransaction();

        try {
            $transaction = $this->transaction->find($id);


            if (!$transaction) {
                return $this->notFoundResponse('transaction not found');
            }

            $transaction->update($data);

            DB::commit();
            return $this->successResponse($transaction, 'transaction updated successfully');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error('Error updating transaction: ' . $e->getMessage());
            return $this->errorResponse('Unable to update transaction');
        }
    }

    /**
     * Delete an transaction by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function delete($id): array
    {
        DB::beginTransaction();

        try {
            $transaction = $this->transaction->find($id);

            if (!$transaction) {
                return $this->notFoundResponse('transaction not found');
            }


            $transaction->delete();
            DB::commit();
            return $this->successResponse(null, 'transaction deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return $this->errorResponse('Unable to delete transaction');
        }
    }
}
