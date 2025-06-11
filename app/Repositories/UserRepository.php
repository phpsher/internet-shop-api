<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\User;
use Exception;

readonly class UserRepository implements UserRepositoryInterface
{
    /**
     * @throws InternalServerErrorException
     */
    public function create(array $data): User
    {
        try {
            $user = User::create($data);
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $user;
    }

    /**
     * @throws InternalServerErrorException
     */
    public function findByEmail(string $email): ?User
    {
        try {
            $user = User::where('email', $email)->first();
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $user;
    }
}
