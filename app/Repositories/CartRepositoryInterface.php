<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

interface CartRepositoryInterface
{
    public function getCartByUserId(int $userId);
    public function addToCart(Request $request);
    public function updateQuantity(Request $request, CartItem $cartItem);
    public function removeItem(CartItem $cartItem);
}
