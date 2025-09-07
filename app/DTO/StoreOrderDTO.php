<?php

namespace App\DTO;

class StoreOrderDTO
{
    /**
     * @param string $userId
     * @param array|null $products
     * @param string|null $totalPrice
     */
    public function __construct(
        public string  $userId,
        public ?array  $products = null,
        public ?string $totalPrice = null,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'user_id'     => $this->userId,
            'products'    => $this->products,
            'total_price' => $this->totalPrice,
        ], fn($value) => $value !== null);
    }
}
