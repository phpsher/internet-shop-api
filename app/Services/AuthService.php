<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\InvalidCredentialsException;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

readonly class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @throws InternalServerErrorException
     */
    public function register(array $data): array
    {
        try {
            $user = $this->userRepository->create($data);
            $token = $user->createToken('token')->plainTextToken;
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }


        return [
            'user' => $user,
            'token' => $token,
        ];

    }

    /**
     * @throws InvalidCredentialsException
     * @throws InternalServerErrorException
     */
    public function login(array $credentials): array
    {
        try {
            $user = $this->userRepository->findByEmail($credentials['email']);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage());
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new InvalidCredentialsException();
        }

        $token = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }
}
