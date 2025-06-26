<?php

namespace App\Repositories;

use App\Contracts\Repositories\CartRepositoryInterface;
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
     * @param array $cartData
     * @param string $cartKey
     * @return array
     * @throws CacheException|InternalServerErrorException
     */
    public function store(array $cartData, string $cartKey): array
    {
        $productId = $cartData['product_id'];
        $quantity = $cartData['quantity'];

        return $this->safe(function () use ($cartKey, $productId, $quantity) {
            $cart = Cache::get($cartKey, []);

            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;
            } else {
                $cart[$productId] = $quantity;
            }

            if (!Cache::put($cartKey, $cart, $this->ttl)) {
                throw new CacheException();
            }

            return $this->formatCart($cart);
        });
    }

    /**
     * @param array $cartData
     * @param string $cartKey
     * @return void
     * @throws CacheException|InternalServerErrorException
     */
    public function delete(array $cartData, string $cartKey): void
    {
        $productId = $cartData['product_id'];

        $this->safe(function () use ($cartKey, $productId) {
            $cart = Cache::get($cartKey, []);

            if (isset($cart[$productId])) {
                unset($cart[$productId]);
            }

            if (!Cache::put($cartKey, $cart, $this->ttl)) {
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
