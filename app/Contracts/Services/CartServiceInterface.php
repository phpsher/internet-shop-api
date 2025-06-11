<?php

namespace App\Contracts\Services;

interface CartServiceInterface
{
    public function getCart(string $cartKey): array;

    public function saveProductToCart(array $data, string $cartKey): array;

    public function deleteProductFromCart(array $data, string $cartKey);
}
