<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected AuthServiceInterface $authService
    )
    {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());


        return $this->success(
            message: 'Successfully register',
            data: [
                'user' => $user['user'],
                'token' => $user['token']
            ],
        );
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());

        return $this->success(
            message: 'Successfully login',
            data: [
                'user' => $user['user'],
                'token' => $user['token']
            ],
        );
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->success(
            message: 'Successfully logout',
        );
    }
}
