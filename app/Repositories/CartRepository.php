<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Service;
use App\Models\CartItem;

class CartRepository implements CartRepositoryInterface
{
    public function getCart(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->with('items.product', 'items.services')
            ->first();
    }

    public function getOrCreateCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function addOrUpdateCartItem(Cart $cart, array $data): CartItem
    {
        $cartItem = $cart->items()->where('product_id', $data['product_id'])->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $data['quantity']]);
        } else {
            $cartItem = $cart->items()->create($data);
        }

        return $cartItem;
    }

    public function attachServicesToCartItem(CartItem $cartItem, array $serviceIds): void
    {
        foreach ($serviceIds as $serviceId) {
            $service = Service::find($serviceId);
            if ($service) {
                $cartItem->services()->attach($service->id, ['price' => $service->price]);
            }
        }
    }

    public function updateQuantity(CartItem $cartItem, int $quantity): void
    {
        $cartItem->update(['quantity' => $quantity]);
    }

    public function removeItem(CartItem $cartItem): void
    {
        $cartItem->services()->detach();
        $cartItem->delete();
    }
}
