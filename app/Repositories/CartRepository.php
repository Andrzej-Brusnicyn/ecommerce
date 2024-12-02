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
    private function getOrCreateCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Add or update a cart item.
     *
     * @param Cart $cart
     * @param array $data
     * @return CartItem
     */
    private function addOrUpdateCartItem(Cart $cart, array $data): CartItem
    {
        $cartItem = $cart->items()->where('product_id', $data['product_id'])->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity']->save();
        } else {
            $cartItem = $cart->items()->create($data);
        }

        return $cartItem;
    }

    /**
     * Attach services to a cart item.
     *
     * @param CartItem $cartItem
     * @param array $serviceIds
     * @return void
     */
    private function attachServicesToCartItem(CartItem $cartItem, array $serviceIds): void
    {
        if (!empty($serviceIds)) {
            foreach ($serviceIds as $serviceId) {
                $service = Service::find($serviceId);
                if ($service) {
                    $cartItem->services()->attach($service->id, ['price' => $service->price]);
                }
            }
        }
    }

    /**
     * Add a product to the cart or update its quantity.
     *
     * @param Request $request
     * @return Cart
     */
    public function addToCart(Request $request): Cart
    {
        $data = $request->only(['product_id', 'quantity']);
        $services = $request->input('services', []);

        $cart = $this->getOrCreateCart(auth()->id());
        $cartItem = $this->addOrUpdateCartItem($cart, $data);
        $this->attachServicesToCartItem($cartItem, $services);

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
