<?php

namespace App\Services;

use App\Models\Cart;
use App\DTO\CreateOrderDTO;
use App\Repositories\OrderRepositoryInterface;
use App\Exceptions\CartEmptyException;
use App\Enums\OrderStatus;
use Psr\Clock\ClockInterface;

class OrderService
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, ClockInterface $clock)
    {
        $this->orderRepository = $orderRepository;
        $this->clock = $clock;
    }

    public function createOrder(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)
            ->with('items.services', 'items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            throw new CartEmptyException('Cart is empty.');
        }

        $totalAmount = $cart->items->sum(function ($item) {
            $itemTotal = $item->quantity * $item->product->price;
            $serviceTotal = $item->services->sum('price');

            return $itemTotal + $serviceTotal;
        });

        $orderDTO = new CreateOrderDTO(
            $userId,
            $totalAmount,
            $this->clock->now(),
            OrderStatus::InProcess
        );

        $this->orderRepository->createOrder($orderDTO, $cart);
    }
}
