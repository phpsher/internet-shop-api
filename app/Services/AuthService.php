<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\DTO\LoginUserDTO;
use App\DTO\RegisterUserDTO;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

readonly class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @param RegisterUserDTO $DTO
     * @return array
     */
    public function register(RegisterUserDTO $DTO): array
    {
        $user = $this->userRepository->store($DTO);
        $token = $user->createToken('token')->plainTextToken;


        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * @param LoginUserDTO $DTO
     * @return array
     */
    public function login(LoginUserDTO $DTO): array
    {
        $user = $this->userRepository->findByEmail($DTO->email);

        if (!$user || !Hash::check($DTO->password, $user->password)) {
            throw new InvalidCredentialsException('Invalid email or password.');
        }

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
