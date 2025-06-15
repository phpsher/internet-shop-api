<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_user_get_all_orders()
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/v1/orders');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'total_price',
                    'created_at',
                    'updated_at',
                    'products' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'price',
                            'image',
                            'created_at',
                            'updated_at',
                            'pivot' => [
                                'order_id',
                                'product_id',
                                'quantity',
                                'total_price',
                                'created_at',
                                'updated_at'
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function test_user_see_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/v1/orders/$order->id");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'user_id',
                'total_price',
                'created_at',
                'updated_at',
                'products' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'price',
                        'image',
                        'created_at',
                        'updated_at',
                        'pivot' => [
                            'order_id',
                            'product_id',
                            'quantity',
                            'total_price',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]]
        ]);
    }

    public function test_successful_order_creation()
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(2)->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $productIds = [];
        foreach ($products as $product) {
            $productIds[] = $product->id;
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/v1/orders', [
            'products' => [
                ['product_id' => $productIds[0], 'quantity' => 2],
                ['product_id' => $productIds[1], 'quantity' => 3]
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'order' => [
                        'user_id',
                        'total_price',
                        'updated_at',
                        'created_at',
                        'id'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => ($products[0]->price * 2) + ($products[1]->price * 3)
        ]);
    }
}
