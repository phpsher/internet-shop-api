<?php

namespace App\Contracts\Repositories;

use App\DTO\RegisterUserDTO;
use App\Models\User;

interface UserRepositoryInterface
{
    public function store(RegisterUserDTO $DTO): User;
    public function findByEmail(string $email): ?User;
}
