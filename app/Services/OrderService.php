<?php

namespace App\Services;

use App\Models\Cart;
use App\Exceptions\CartEmptyException;

class OrderService
{
    /**
     * Calculate total amount for the order and validate the cart.
     *
     * @param int $userId
     * @return array
     * @throws \Exception
     */
    public function prepareOrderData(int $userId): array
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

        return [
            'cart' => $cart,
            'totalAmount' => $totalAmount
        ];
    }
}
