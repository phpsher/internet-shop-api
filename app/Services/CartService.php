<?php

namespace App\Services;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Services\CartServiceInterface;
use App\Exceptions\InternalServerErrorException;

readonly class CartService implements CartServiceInterface
{

    public function __construct(
        protected CartRepositoryInterface $cartRepository,
    )
    {
    }

    /**
     * @throws InternalServerErrorException
     */
    public function getCart(string $cartKey): array
    {
        try {
            $cart = $this->cartRepository->getByKey($cartKey);
        } catch (InternalServerErrorException $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $cart;
    }

    /**
     * @throws InternalServerErrorException
     */
    public function saveProductToCart(array $data, string $cartKey): array
    {
        $productId = $data['product_id'];
        $quantity = $data['quantity'];

        try {
            $cart = $this->cartRepository->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ], $cartKey);
        } catch (InternalServerErrorException $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $cart;
    }

    /**
     * @throws InternalServerErrorException
     */
    public function deleteProductFromCart(array $data, string $cartKey): void
    {
        $productId = $data['product_id'];
        $quantity = $data['quantity'];

        try {
            $this->cartRepository->delete([
                'product_id' => $productId,
                'quantity' => $quantity,
            ], $cartKey);
        } catch (InternalServerErrorException $e) {
            throw new InternalServerErrorException($e->getMessage());
        }
    }
}
