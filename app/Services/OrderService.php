<?php

namespace App\Services;

use App\DTO\OrderStatus;
use App\Models\Cart;
use App\Repositories\OrderRepositoryInterface;
use App\DTO\CreateOrderDTO;
use App\DTO\CreateOrderItemDTO;

class OrderService
{
    protected OrderRepositoryInterface $orderRepository;

    /**
     * OrderService constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Create a new order.
     *
     * @param int $userId
     * @throws \Exception
     */
    public function createOrder(int $userId): void
    {
        try {
            $cart = Cart::where('user_id', $userId)
                ->with('items.services', 'items.product')
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception('Cart empty.', 1001);
            }

            $totalAmount = $cart->items->sum(function ($item) {
                $itemTotal = $item->quantity * $item->product->price;
                $serviceTotal = $item->services->sum('price');
                return $itemTotal + $serviceTotal;
            });

            $orderDTO = new CreateOrderDTO(
                $userId,
                $totalAmount,
                now()->toDateTimeString(),
                OrderStatus::InProcess
            );
            $order = $this->orderRepository->createOrder($orderDTO->toArray());

            foreach ($cart->items as $cartItem) {
                $orderItemDTO = new CreateOrderItemDTO($cartItem);
                $orderItem = $order->items()->create($orderItemDTO->toArray());

                foreach ($orderItemDTO->services as $service) {
                    $orderItem->services()->attach($service->id, ['price' => $service->price]);
                }
            }

            $cart->items()->delete();
        } catch (\Exception $e) {
            echo "Error creating order: " . $e->getMessage() . " (Code: " . $e->getCode() . ")";
        }
    }
}
