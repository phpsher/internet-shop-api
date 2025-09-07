<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTO\RegisterUserDTO;
use App\Exceptions\InternalServerErrorException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * @param RegisterUserDTO $DTO
     * @return User
     * @throws InternalServerErrorException
     */
    public function store(RegisterUserDTO $DTO): User
    {
        return $this->safe(function () use ($DTO) {
            $user = User::create($DTO->toArray());

            // Кэширую именно email т.к. есть метод findByEmail
            Cache::put("user:email:$user->email", $user, $this->ttl);

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
                return User::where('email', $email)->firstOrFail();
            });
        });
    }
}
