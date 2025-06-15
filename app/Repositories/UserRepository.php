<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\User;

readonly class UserRepository implements UserRepositoryInterface
{
    /**
     * @param array $userData
     * @return User
     */
    public function store(array $userData): User
    {
        return User::create($userData);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        $user = User::where('email', $email)->first();

        return $user;
    }
}
