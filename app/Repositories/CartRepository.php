<?php

namespace App\Repositories;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\DTO\AddProductToCartDTO;
use App\DTO\DestroyProductFromCartDTO;
use App\Exceptions\CacheException;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\Cache;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    /**
     * @param string $cartKey
     * @return array
     * @throws InternalServerErrorException
     */
    public function getByKey(string $cartKey): array
    {
        return $this->safe(function () use ($cartKey) {
            return $this->formatCart(Cache::get($cartKey, []));
        });
    }

    /**
     * @param AddProductToCartDTO $DTO
     * @return array
     * @throws InternalServerErrorException
     */
    public function store(AddProductToCartDTO $DTO): array
    {
        return $this->safe(function () use ($DTO) {
            $cartKey = $DTO->cartKey;
            $productId = $DTO->productId;
            $quantity = (int) $DTO->quantity;

            $cart = Cache::get($cartKey, []);

            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;
            } else {
                $cart[$productId] = $quantity;
            }

            if (!Cache::put($cartKey, $cart, $this->ttl)) {
                throw new CacheException('Failed to store cart in cache');
            }

            return $this->formatCart($cart);
        });
    }


    /**
     * @param DestroyProductFromCartDTO $DTO
     * @return void
     * @throws InternalServerErrorException
     */
    public function delete(DestroyProductFromCartDTO $DTO): void
    {
        $this->safe(function () use ($DTO) {
            $cart = Cache::get($DTO->cartKey, []);

            if (isset($cart[$DTO->productId])) {
                unset($cart[$DTO->productId]);
            }

            if (!Cache::put($DTO->cartKey, $cart, $this->ttl)) {
                throw new CacheException('Error deleting product from cart');
            }
        });
    }

    /**
     * @param array $cart
     * @return mixed
     */
    private function formatCart(array $cart): array
    {
        $formatted = [];
        foreach ($cart as $productId => $quantity) {
            $formatted[] = [
                'product_id' => (int) $productId,
                'quantity' => (int) $quantity,
            ];
        }
        return $formatted;
    }
}
