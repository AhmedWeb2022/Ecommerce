<?php

namespace App\Services\Auth;

use App\Http\Resources\User\UserResource;
use App\Params\Auth\LoginParam;
use App\Traits\ApiResponseTrait;
use App\Params\Auth\RegisterParam;
use App\Repositories\User\UserRepository;

class AuthService
{
    use ApiResponseTrait;

    protected $loginParam;
    protected $registerParam;
    protected $userRepository;

    public function __construct(LoginParam $loginParam, RegisterParam $registerParam, UserRepository $userRepository)
    {
        $this->loginParam = $loginParam;
        $this->registerParam = $registerParam;
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $response = $this->userRepository->register($this->registerParam->setParams($data)->toArray());
        // dd($response['data']);
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(new UserResource($response['data']), 'Orders retrieved successfully');
    }

    public function login(array $data)
    {
        $params = $this->loginParam->setParams($data);
        $response = $this->userRepository->login($params->toArray());
        if (!$response['status']) {
            return $this->error($response['message']);
        }

        return $this->success(new UserResource($response['data']), 'Orders retrieved successfully');
    }

    public function logout()
    {
        auth('api')->user()->currentAccessToken()->delete();

        return $this->successMessage('Logout successful');
    }
}
