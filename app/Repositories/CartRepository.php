<?php

namespace App\Repositories;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Exceptions\InternalServerErrorException;
use Exception;
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
     * @throws InternalServerErrorException
     */

    public function getByKey(string $cartKey): array
    {
        try {
            $cart = Cache::get($cartKey, []);
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

            return $this->formatCart($cart);
    }

    /**
     * @param array $data
     * @param string $cartKey
     * @return array
     * @throws InternalServerErrorException
     */
    public function create(array $data, string $cartKey): array
    {
        $productId = $data['product_id'];
        $quantity = $data['quantity'];

        try {
            $cart = Cache::get($cartKey, []);
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        try {
            Cache::put($cartKey, $cart, $this->ttl);
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }


        return $this->formatCart($cart);
    }

    /**
     * @param array $data
     * @param string $cartKey
     * @return void
     * @throws InternalServerErrorException
     */
    public function delete(array $data, string $cartKey): void
    {
        $productId = $data['product_id'];

        try {
            $cart = Cache::get($cartKey, []);
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        try {
            Cache::put($cartKey, $cart, $this->ttl);
        } catch (Exception $e) {
            throw new InternalServerErrorException($e->getMessage());
        }
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
