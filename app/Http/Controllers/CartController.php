<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\AuthService;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService, AuthService $authService)
    {
        $this->cartService = $cartService;
        $this->authService = $authService;
    }

    public function index(): View
    {
        $userId = $this->authService->getUserId();
        $cartData = $this->cartService->getCartData($userId);

        return view('cart', [
            'cart' => $cartData['cart'],
            'totalAmount' => $cartData['totalAmount'],
        ]);
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $userId = $this->authService->getUserId();
        $this->cartService->addItemToCart($request->all(), $userId);

        return redirect()->route('cart.index')
            ->with('message', 'You have successfully added the product and selected services to the cart!');
    }

    public function updateQuantity(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->cartService->updateCartItemQuantity($cartItem, $request->input('quantity'));

        return redirect()->route('cart.index')
            ->with('message', 'You have successfully changed the quantity of the product!');
    }

    public function removeItem(CartItem $cartItem): RedirectResponse
    {
        $this->cartService->removeItemFromCart($cartItem);

        return redirect()->route('cart.index')
            ->with('message', 'You have successfully removed the product from the cart!');
    }
}
