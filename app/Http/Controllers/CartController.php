<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Service;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product', 'services')
            ->first();

        $totalAmount = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            }) + $cart->services->sum('price');

        return view('cart', compact('cart', 'totalAmount'));
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

        return redirect()->route('cart')
            ->with('message', 'Вы успешно добавили товар и выбранные услуги в корзину!');
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $cartItem->update(['quantity' => $request->input('quantity')]);
        return redirect()->route('cart')->with('message', 'Вы успешно изменили количество товара!');
    }

    public function removeItem(CartItem $cartItem)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cartItem->delete();
        $cart->services()->detach();
        return redirect()->route('cart')->with('message', 'Вы успешно удалили товар из корзины!');
    }
}
