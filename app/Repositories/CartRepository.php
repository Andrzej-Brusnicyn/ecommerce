<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Service;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartRepository implements CartRepositoryInterface
{
    protected CartService $cartService;

    /**
     * CartRepository constructor.
     *
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Get the cart and calculate the total amount.
     *
     * @param int $userId
     * @return array
     */
    public function getCartWithTotal(int $userId): array
    {
        $cart = Cart::where('user_id', $userId)
            ->with('items.product', 'items.services')
            ->first();

        $totalAmount = $cart ? $this->cartService->calculateTotalAmount($cart) : 0;

        return compact('cart', 'totalAmount');
    }

    /**
     * Add an item to the cart.
     *
     * @param Request $request
     * @return void
     */
    public function addToCart(Request $request, $userId): void
    {
        $cart = $this->getOrCreateCart($userId);

        $data = $request->only(['product_id', 'quantity']);
        $services = $request->input('services', []);

        $cartItem = $this->addOrUpdateCartItem($cart, $data);
        $this->attachServicesToCartItem($cartItem, $services);
    }

    /**
     * Update the quantity of an item in the cart.
     *
     * @param Request $request
     * @param CartItem $cartItem
     * @return void
     */
    public function updateQuantity(Request $request, CartItem $cartItem): void
    {
        $cartItem->update(['quantity' => $request->input('quantity')]);
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

    /**
     * Get or create a cart for the user.
     *
     * @param int $userId
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
            $cartItem->update(['quantity' => $cartItem->quantity + $data['quantity']]);
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
}
