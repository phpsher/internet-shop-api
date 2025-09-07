<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\DTO\StoreOrderDTO;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface   $orderRepository,
        private ProductRepositoryInterface $productRepository,
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getOrders(): LengthAwarePaginator // Возвращает ВСЕ заказы
    {
        return $this->orderRepository->all();
    }


    /**
     * @param string $orderId
     * @return Order|null
     */
    public function getOrder(string $orderId): ?Order // Возвращает ОДИН закакз по id
    {
        return $this->orderRepository->getOrder($orderId);
    }

    /**
     * @param StoreOrderDTO $DTO
     * @return Order
     */
    public function storeOrder(StoreOrderDTO $DTO): Order
    {
        return DB::transaction((function () use ($DTO) {
            $productsIds = array_map(function ($item) {
                return $item['product_id'];
            }, $DTO->products);

            $products = $this->productRepository->getAllByIds($productsIds);

            $totalPrice = 0;
            foreach ($DTO->products as $item) {
                if (isset($products[$item['product_id']])) {
                    $product = $products[$item['product_id']];
                    $totalPrice += $product->price * $item['quantity'];
                }
            }

            $order = $this->orderRepository->store(
                new StoreOrderDTO(
                    userId: $DTO->userId,
                    totalPrice: $totalPrice,
                )
            );


            $attachData = [];
            foreach ($DTO->products as $item) {
                $product = $products[$item['product_id']];
                $attachData[$product->id] = [
                    'quantity'    => $item['quantity'],
                    'total_price' => $product->price * $item['quantity'],
                ];
            }

            $order->products()->attach($attachData);


            return $order;
        }));
    }
}
