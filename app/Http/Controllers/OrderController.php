<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Services\AuthService;
use App\Services\OrderService;
use App\Exceptions\CartEmptyException;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService, AuthService $authService)
    {
        $this->orderService = $orderService;
        $this->authService = $authService;
    }

    public function createOrder(): JsonResponse|RedirectResponse
    {
        $userId = $this->authService->getUserId();

        if ($userId === null) {
            return redirect()->route('login')->with('message', 'Please log in to create an order.');
        }

        try {
            $this->orderService->createOrder($userId);

            return redirect()->route('products.index');
        } catch (CartEmptyException $e) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }
}

