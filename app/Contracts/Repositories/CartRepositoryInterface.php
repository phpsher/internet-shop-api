<?php

namespace App\Contracts\Repositories;

use App\DTO\AddProductToCartDTO;
use App\DTO\DestroyProductFromCartDTO;

interface CartRepositoryInterface
{
    public function getByKey(string $cartKey): array;

    public function store(AddProductToCartDTO $DTO);

    public function delete(DestroyProductFromCartDTO $DTO);
}
