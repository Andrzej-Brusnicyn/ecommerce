<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

interface CartRepositoryInterface
{
    public function attachServicesToCartItem(CartItem $cartItem, array $serviceIds): void;
    public function addOrUpdateCartItem(Cart $cart, array $data): CartItem;
    public function getOrCreateCart(int $userId): Cart;
    public function getCart(int $userId): ?Cart;
    public function updateQuantity(CartItem $cartItem, int $quantity);
    public function removeItem(CartItem $cartItem);
}
