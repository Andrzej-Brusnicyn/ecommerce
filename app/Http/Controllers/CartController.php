<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Repositories\CartRepositoryInterface;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\AuthService;

class CartController extends Controller
{
    protected CartRepositoryInterface $cartRepository;
    protected CartService $cartService;
    protected AuthService $authService;

    /**
     * CartController constructor.
     *
     * @param CartRepositoryInterface $cartRepository
     * @param CartService $cartService
     * @param AuthService $authService
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartService $cartService,
        AuthService $authService
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
        $this->authService = $authService;
    }

    /**
     * Display the cart contents.
     *
     * @return View
     */
    public function index(): View
    {
        $userId = $this->authService->getUserId();
        $cart = $this->cartRepository->getCartByUserId($userId);
        $totalAmount = $this->cartService->calculateTotalAmount($cart);

        return view('cart', compact('cart', 'totalAmount'));
    }

    /**
     * Add an item to the cart.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addToCart(Request $request): RedirectResponse
    {
        $this->cartRepository->addToCart($request);

        return redirect()->route('cart.index')
            ->with('message', 'You have successfully added the product and selected services to the cart!');
    }

    /**
     * Update the quantity of an item in the cart.
     *
     * @param Request $request
     * @param CartItem $cartItem
     * @return RedirectResponse
     */
    public function updateQuantity(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->cartRepository->updateQuantity($request, $cartItem);

        return redirect()->route('cart.index')
            ->with('message', 'You have successfully changed the quantity of the product!');
    }

    /**
     * Remove an item from the cart.
     *
     * @param CartItem $cartItem
     * @return RedirectResponse
     */
    public function removeItem(CartItem $cartItem): RedirectResponse
    {
        $this->cartRepository->removeItem($cartItem);

        return redirect()->route('cart.index')
            ->with('message', 'You have successfully removed the product from the cart!');
    }
}
