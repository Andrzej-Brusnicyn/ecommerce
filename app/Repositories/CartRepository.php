<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartRepository implements CartRepositoryInterface
{
    public function getCartByUserId(int $userId)
    {
        return Cart::where('user_id', $userId)
            ->with('items.product', 'services')
            ->first();
    }

    public function addToCart(Request $request)
    {
        $data = $request->only(['product_id', 'quantity']);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = $cart->items()->where('product_id', $data['product_id'])->first();
        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            $cart->items()->create($data);
        }

        if ($request->filled('services')) {
            $cart->services()->sync($request->input('services', []));
        }

        return $cart;
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $cartItem->update(['quantity' => $request->input('quantity')]);
        return $cartItem;
    }

    public function removeItem(CartItem $cartItem)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cartItem->delete();
        $cart->services()->detach();
    }
}
