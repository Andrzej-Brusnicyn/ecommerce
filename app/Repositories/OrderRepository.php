<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }
}
