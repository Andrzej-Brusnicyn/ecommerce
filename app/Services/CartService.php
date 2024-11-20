<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    /**
     * Calculate the total amount of the cart.
     *
     * @param Cart $cart
     * @return float
     */
    public function calculateTotalAmount(Cart $cart): float
    {
        $itemTotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $serviceTotal = $cart->items->sum(function ($item) {
            return $item->services->sum('pivot.price');
        });

        return $itemTotal + $serviceTotal;
    }
}
