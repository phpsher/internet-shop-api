<?php

namespace App\Contracts\Services;


interface AuthServiceInterface
{
    public function register(array $data): array;

    public function login(array $credentials): array;

    public function logout(): void;
}
