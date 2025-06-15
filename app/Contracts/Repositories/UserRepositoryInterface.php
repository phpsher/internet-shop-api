<?php

namespace App\Contracts\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function store(array $userData): User;

    public function findByEmail(string $email): ?User;

}
