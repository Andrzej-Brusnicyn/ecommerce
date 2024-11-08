<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $userId = auth()->id();
        $cart = Cart::where('user_id', $userId)->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Корзина пуста.'], 400);
        }

        $totalAmount = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $order = Order::create([
            'user_id' => $userId,
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'status' => 'in process'
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        $cart->items()->delete();

        return redirect()->route('catalog')->with('message', 'Заказ успешно оформлен!');
    }
}
