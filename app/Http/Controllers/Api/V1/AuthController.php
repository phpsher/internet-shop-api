<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\AuthServiceInterface;
use App\Enums\HttpStatus;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        try {
            $user = $this->authService->login($request->validated());
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: $e->getMessage(),
                statusCode: HttpStatus::NOT_FOUND->value,
            );
        } catch (InternalServerErrorException $e) {
            return $this->error(
                message: $e->getMessage(),
                statusCode: HttpStatus::INTERNAL_SERVER_ERROR->value,
            );
        } catch (InvalidCredentialsException $e) {
            return $this->error(
                message: $e->getMessage(),
                statusCode: HttpStatus::UNAUTHORIZED->value,
            );
        }

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
