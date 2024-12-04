<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use App\DTO\CreateOrderDTO;
use App\DTO\CreateOrderItemDTO;
use Psr\Clock\ClockInterface;
use App\Exceptions\OrderCreationException;

class OrderRepository implements OrderRepositoryInterface
{
    protected OrderService $orderService;

    /**
     * OrderRepository constructor.
     *
     * @param OrderService $orderService
     * @param ClockInterface $clock
     */
    public function __construct(OrderService $orderService, ClockInterface $clock)
    {
        $this->orderService = $orderService;
        $this->clock = $clock;
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
            $data = $this->orderService->prepareOrderData($userId);
            $cart = $data['cart'];
            $totalAmount = $data['totalAmount'];

            $orderDTO = new CreateOrderDTO(
                $userId,
                $totalAmount,
                $this->clock->now(),
                OrderStatus::InProcess
            );

            $order = Order::create($orderDTO->toArray());

            foreach ($cart->items as $cartItem) {
                $orderItemDTO = new CreateOrderItemDTO($cartItem);
                $orderItem = $order->items()->create($orderItemDTO->toArray());

                foreach ($orderItemDTO->services as $service) {
                    $orderItem->services()->attach($service->id, ['price' => $service->price]);
                }
            }

            $cart->items()->delete();
        } catch (\Exception $e) {
            throw new OrderCreationException('Error creating order: ' . $e->getMessage());
        }
    }
}
