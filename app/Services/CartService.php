<?php

namespace App\Services;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Services\CartServiceInterface;

readonly class CartService implements CartServiceInterface
{

    public function __construct(
        protected CartRepositoryInterface $cartRepository,
    )
    {
    }

    /**
     * @param string $cartKey
     * @return array
     */
    public function getCart(string $cartKey): array
    {

        return $this->cartRepository->getByKey($cartKey);
    }

    /**
     * @param array $productsData
     * @param string $cartKey
     * @return array
     */
    public function addProductToCart(array $productsData, string $cartKey): array
    {
        $productId = $productsData['product_id'];
        $quantity = $productsData['quantity'];

        return $this->cartRepository->store([
            'product_id' => $productId,
            'quantity' => $quantity,
        ], $cartKey);
    }

    /**
     * @param array $productsData
     * @param string $cartKey
     * @return void
     */
    public function deleteProductFromCart(array $productsData, string $cartKey): void
    {
        $productId = $productsData['product_id'];
        $quantity = $productsData['quantity'];

        $this->cartRepository->delete([
            'product_id' => $productId,
            'quantity' => $quantity,
        ], $cartKey);
    }
}
