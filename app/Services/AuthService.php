<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidCredentialsException;
use Auth;
use Illuminate\Support\Facades\Hash;

readonly class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @param array $userData
     * @return array
     */
    public function register(array $userData): array
    {
        $user = $this->userRepository->store($userData);
        $token = $user->createToken('token')->plainTextToken;


        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        $token = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }
}
