<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Cart;
use App\DTO\CreateOrderDTO;

interface OrderRepositoryInterface
{
    public function createOrder(CreateOrderDTO $orderDTO, Cart $cart): Order;
}
