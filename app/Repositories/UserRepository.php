<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

readonly class UserRepository implements UserRepositoryInterface
{
    private int $ttl;

    public function __construct()
    {
        $this->ttl = 3600 * 24 * 7;
    }

    /**
     * @param array $userData
     * @return User
     */
    public function store(array $userData): User
    {
        $user = User::create($userData);

        Cache::put("user:$user->id", $user, $this->ttl);

        return $user;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return Cache::remember('user:email:' . $email, $this->ttl, function () use ($email) {
           return User::where('email', $email)->first();
        });
    }
}
