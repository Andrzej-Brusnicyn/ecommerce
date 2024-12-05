<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Cart;
use App\DTO\CreateOrderItemDTO;
use App\DTO\CreateOrderDTO;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(CreateOrderDTO $orderDTO, Cart $cart): Order
    {
        $order = Order::create($orderDTO->toArray());

        foreach ($cart->items as $cartItem) {
            $orderItemDTO = new CreateOrderItemDTO($cartItem);
            $orderItem = $order->items()->create($orderItemDTO->toArray());

            foreach ($orderItemDTO->services as $service) {
                $orderItem->services()->attach($service->id, ['price' => $service->price]);
            }
        }

        $cart->items()->delete();

        return $order;
    }
}
