<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * @param array $userData
     * @return User
     * @throws InternalServerErrorException
     */
    public function store(array $userData): User
    {
        return $this->safe(function () use ($userData) {
            $user = User::create($userData);

            Cache::put("user:$user->id", $user, $this->ttl);

            return $user;
        });
    }

    /**
     * @param string $email
     * @return User|null
     * @throws InternalServerErrorException
     */
    public function findByEmail(string $email): ?User
    {
        return $this->safe(function () use ($email) {
            return Cache::remember('user:email:' . $email, $this->ttl, function () use ($email) {
                return User::where('email', $email)->first();
            });
        });
    }
}
