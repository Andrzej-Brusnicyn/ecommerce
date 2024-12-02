<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Service;
use Illuminate\Http\Request;

class CartRepository implements CartRepositoryInterface
{
    /**
     * Get the cart by user ID.
     *
     * @param int $userId
     * @return Cart|null
     */
    public function getCartByUserId(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->with('items.product', 'items.services')
            ->first();
    }

    /**
     * Add an item to the cart.
     *
     * @param Request $request
     * @return Cart
     */
    public function addToCart(Request $request): Cart
    {
        $data = $request->only(['product_id', 'quantity']);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = $cart->items()->where('product_id', $data['product_id'])->first();
        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            $cartItem = $cart->items()->create($data);
        }

        if ($request->filled('services')) {
            foreach ($request->input('services', []) as $serviceId) {
                $service = Service::find($serviceId);
                if ($service) {
                    $cartItem->services()->attach($service->id, ['price' => $service->price]);
                }
            }
        }

        return $cart;
    }

    /**
     * Update the quantity of an item in the cart.
     *
     * @param Request $request
     * @param CartItem $cartItem
     * @return CartItem
     */
    public function updateQuantity(Request $request, CartItem $cartItem): CartItem
    {
        $cartItem->update(['quantity' => $request->input('quantity')]);

        return $cartItem;
    }

    /**
     * Remove an item from the cart.
     *
     * @param CartItem $cartItem
     * @return void
     */
    public function removeItem(CartItem $cartItem): void
    {
        $cartItem->services()->detach();

        $cartItem->delete();
    }
}
