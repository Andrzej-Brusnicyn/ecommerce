<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Repositories\CartRepositoryInterface;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartRepository;
    protected $cartService;

    public function __construct(CartRepositoryInterface $cartRepository, CartService $cartService)
    {
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartRepository->getCartByUserId(auth()->id());
        $totalAmount = $this->cartService->calculateTotalAmount($cart);

        return view('cart', compact('cart', 'totalAmount'));
    }

    public function addToCart(Request $request)
    {
        $this->cartRepository->addToCart($request);

        return redirect()->route('cart')
            ->with('message', 'Вы успешно добавили товар и выбранные услуги в корзину!');
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $this->cartRepository->updateQuantity($request, $cartItem);
        return redirect()->route('cart')->with('message', 'Вы успешно изменили количество товара!');
    }

    public function removeItem(CartItem $cartItem)
    {
        $this->cartRepository->removeItem($cartItem);
        return redirect()->route('cart')->with('message', 'Вы успешно удалили товар из корзины!');
    }
}
