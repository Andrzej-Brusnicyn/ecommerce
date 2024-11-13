<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    public function calculateTotalAmount(Cart $cart): float
    {
        return $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            }) + $cart->services->sum('price');
    }
}
