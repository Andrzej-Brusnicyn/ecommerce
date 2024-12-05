<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\CartRepositoryInterface;

class CartService
{
    protected CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCartData(int $userId): array
    {
        $cart = $this->cartRepository->getCart($userId);
        $totalAmount = $cart ? $this->calculateTotalAmount($cart) : 0;

        return compact('cart', 'totalAmount');
    }

    public function addItemToCart(array $data, int $userId): void
    {
        $cart = $this->cartRepository->getOrCreateCart($userId);

        $cartItem = $this->cartRepository->addOrUpdateCartItem($cart, [
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
        ]);

        if (!empty($data['services'])) {
            $this->cartRepository->attachServicesToCartItem($cartItem, $data['services']);
        }
    }

    public function updateCartItemQuantity(CartItem $cartItem, int $quantity): void
    {
        $this->cartRepository->updateQuantity($cartItem, $quantity);
    }

    public function removeItemFromCart(CartItem $cartItem): void
    {
        $this->cartRepository->removeItem($cartItem);
    }

    protected function calculateTotalAmount(Cart $cart): float
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
