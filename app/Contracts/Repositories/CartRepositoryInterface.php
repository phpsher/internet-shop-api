<?php

namespace App\Contracts\Repositories;

interface CartRepositoryInterface
{
    public function getByKey(string $cartKey): array;

    public function create(array $data, string $cartKey);

    public function delete(array $data, string $cartKey);
}
