<?php

namespace App\Services;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Services\CartServiceInterface;
use App\DTO\AddProductToCartDTO;
use App\DTO\DestroyProductFromCartDTO;

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
     * @param AddProductToCartDTO $DTO
     * @return array
     */
    public function addProductToCart(AddProductToCartDTO $DTO): array
    {
        return $this->cartRepository->store($DTO);
    }

    /**
     * @param DestroyProductFromCartDTO $DTO
     * @return void
     */
    public function deleteProductFromCart(DestroyProductFromCartDTO $DTO): void
    {

        $this->cartRepository->delete($DTO);
    }
}
