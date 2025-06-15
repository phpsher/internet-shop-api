<?php

namespace App\Contracts\Services;

interface CartServiceInterface
{
    public function getCart(string $cartKey): array;

    public function addProductToCart(array $productsData, string $cartKey): array;

    public function deleteProductFromCart(array $productsData, string $cartKey);
}
