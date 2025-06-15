<?php

namespace App\Contracts\Repositories;

interface CartRepositoryInterface
{
    public function getByKey(string $cartKey): array;

    public function store(array $cartData, string $cartKey);

    public function delete(array $cartData, string $cartKey);
}
