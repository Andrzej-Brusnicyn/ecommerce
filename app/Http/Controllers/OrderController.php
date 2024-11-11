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
        $cart = Cart::where('user_id', $userId)->with('items.product', 'services')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Корзина пуста.'], 400);
        }

        // Рассчитываем общую стоимость товаров
        $totalAmount = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Добавляем стоимость услуг к общей сумме
        $totalAmount += $cart->services->sum('price');

        // Создаём заказ
        $order = Order::create([
            'user_id' => $userId,
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'status' => 'in process'
        ]);

        // Добавляем товары из корзины в заказ
        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        // Добавляем услуги из корзины в заказ
        foreach ($cart->services as $service) {
            $order->services()->attach($service->id, ['price' => $service->price]);
        }

        // Очищаем корзину после создания заказа
        $cart->items()->delete();
        $cart->services()->detach();

        return redirect()->route('catalog')->with('message', 'Заказ успешно оформлен!');
    }
}
