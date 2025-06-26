<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface   $orderRepository,
        private ProductRepositoryInterface $productRepository,
    )
    {
    }

    public function getOrders(): LengthAwarePaginator
    {
        return $this->orderRepository->all();
    }

    public function getUserOrders(int $userId): Collection
    {
        return $this->orderRepository->allUserOrders($userId);
    }

    public function getOrder(int $userId, int $orderId): Order
    {
        return $this->orderRepository->getById($userId, $orderId);
    }

    /**
     * @throws Throwable
     */
    public function storeOrder(int $userId, array $productsData): Order
    {
        return DB::transaction((function () use ($userId, $productsData) {
            $productsIds = array_map(function ($item) {
                return $item['product_id'];
            }, $productsData);

            $products = $this->productRepository->getAllByIds($productsIds);

            $totalPrice = 0;
            foreach ($productsData as $item) {
                if (isset($products[$item['product_id']])) {
                    $product = $products[$item['product_id']];
                    $totalPrice += $product->price * $item['quantity'];
                }
            }

            $order = $this->orderRepository->store([
                'user_id' => $userId,
                'total_price' => $totalPrice
            ]);


            $attachData = [];
            foreach ($productsData as $item) {
                $product = $products[$item['product_id']];
                $attachData[$product->id] = [
                    'quantity' => $item['quantity'],
                    'total_price' => $product->price,
                ];
            }

            $order->products()->attach($attachData);


            return $order;
        }));


    }
}
