<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\OrderRepositoryInterface;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(int $userId)
    {
        $cart = Cart::where('user_id', $userId)->with('items.product', 'services')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return ['success' => false, 'message' => 'Корзина пуста.'];
        }

        $totalAmount = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $totalAmount += $cart->services->sum('price');

        $order = $this->orderRepository->createOrder([
            'user_id' => $userId,
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'status' => 'in process'
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        foreach ($cart->services as $service) {
            $order->services()->attach($service->id, ['price' => $service->price]);
        }

        $cart->items()->delete();
        $cart->services()->detach();

        return ['success' => true, 'message' => 'Заказ успешно оформлен!'];
    }
}
