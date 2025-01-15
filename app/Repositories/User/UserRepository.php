<?php

namespace App\Repositories\User;

use Exception;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Models\Transaction\Transaction;
use App\Traits\RepositoryResponseTrait;
use App\Interfaces\Repository\RepositoryInterface;
use App\Models\User;

class UserRepository implements RepositoryInterface
{
    use RepositoryResponseTrait;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Retrieve all users from the database.
     *
     * @return array<string, mixed> An associative array containing the status, message, and data of orders.
     */
    public function getAll(): array
    {
        try {
            $users = $this->user->all();
            return $this->successResponse($users, 'users retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error retrieving all users: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving all users');
        }
    }

    /**
     * Find an user by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function findById($id): array
    {
        try {
            $user = $this->user->find($id);

            if (!$user) {
                return $this->notFoundResponse('user not found');
            }

            return $this->successResponse($user, 'user retrieved successfully');
        } catch (Exception $e) {
            Log::error('Error finding user by ID: ' . $e->getMessage());
            return $this->errorResponse('Error finding user');
        }
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        DB::beginTransaction();

        try {
            $user = $this->user->create($data);
            DB::commit();
            return $this->successResponse($user, 'user created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return $this->errorResponse('Unable to create user');
        }
    }

    /**
     * Update an user by ID.
     *
     * @param int $id
     * @param array $data
     * @return array<string, mixed>
     */
    public function update($id, array $data): array
    {
        DB::beginuser();

        try {
            $user = $this->user->find($id);


            if (!$user) {
                return $this->notFoundResponse('user not found');
            }

            $user->update($data);

            DB::commit();
            return $this->successResponse($user, 'user updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return $this->errorResponse('Unable to update user');
        }
    }

    /**
     * Delete an user by ID.
     *
     * @param int $id
     * @return array<string, mixed>
     */
    public function delete($id): array
    {
        DB::beginuser();

        try {
            $user = $this->user->find($id);

            if (!$user) {
                return $this->notFoundResponse('user not found');
            }


            $user->delete();
            DB::commit();
            return $this->successResponse(null, 'user deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            return $this->errorResponse('Unable to delete user');
        }
    }

    public function register($data)
    {
        try {
            $response = $this->create($data);
            // dd($response);

            if (!$response['status']) {
                return $this->errorResponse($response['message']);
            }
            $response['data']['token'] = $response['data']->createToken($response['data']->email)->plainTextToken;

            return $this->successResponse($response['data'], 'User registered successfully');
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('Error registering user: ' . $e->getMessage());
            return $this->errorResponse('Unable to register user');
        }
    }

    public function login($data)
    {
        try {
            $user = $this->user->where('email', $data['email'])->first();
            if (!$user) {
                return $this->errorResponse('User not found');
            }
            if (!password_verify($data['password'], $user->password)) {
                return $this->errorResponse('Invalid password');
            }

            $user->token = $user->createToken($user->email)->plainTextToken;
            return $this->successResponse($user, 'User logged in successfully');
        } catch (Exception $e) {
            Log::error('Error logging in user: ' . $e->getMessage());
            return $this->errorResponse('Unable to login user');
        }
    }
}
