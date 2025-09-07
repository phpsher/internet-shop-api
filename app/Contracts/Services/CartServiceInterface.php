<?php

namespace App\Contracts\Services;

use App\DTO\AddProductToCartDTO;
use App\DTO\DestroyProductFromCartDTO;

interface CartServiceInterface
{
    public function getCart(string $cartKey): array;

    public function addProductToCart(AddProductToCartDTO $DTO): array;

    public function deleteProductFromCart(DestroyProductFromCartDTO $DTO): void;
}
