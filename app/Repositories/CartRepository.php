<?php

namespace App\Repositories;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\Cache;

readonly class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        private int $ttl = 3600 * 24 * 7,
    )
    {
    }

    /**
     * @param string $cartKey
     * @return array
     */

    public function getByKey(string $cartKey): array
    {
        $cart = Cache::get($cartKey, []);

        return $this->formatCart($cart);
    }

    /**
     * @param array $cartData
     * @param string $cartKey
     * @return array
     */
    public function store(array $cartData, string $cartKey): array
    {
        $productId = $cartData['product_id'];
        $quantity = $cartData['quantity'];

        $cart = Cache::get($cartKey, []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        Cache::put($cartKey, $cart, $this->ttl);


        return $this->formatCart($cart);
    }

    /**
     * @param array $cartData
     * @param string $cartKey
     * @return void
     */
    public function delete(array $cartData, string $cartKey): void
    {
        $productId = $cartData['product_id'];

        $cart = Cache::get($cartKey, []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        Cache::put($cartKey, $cart, $this->ttl);
    }

    /**
     * @param array $cart
     * @return mixed
     */
    private function formatCart(array $cart): array
    {
        return array_map(function ($prodId, $qty) {
            return [
                'product_id' => (int)$prodId,
                'quantity' => (int)$qty,
            ];
        }, array_keys($cart), $cart);
    }
}
