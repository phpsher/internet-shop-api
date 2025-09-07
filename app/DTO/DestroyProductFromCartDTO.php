<?php

namespace App\DTO;

class DestroyProductFromCartDTO
{
    /**
     * @param string $productId
     * @param string $quantity
     * @param string $cartKey
     */
    public function __construct(
        public string $productId,
        public string $quantity,
        public string $cartKey,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'cartKey' => $this->cartKey,
        ];
    }
}
