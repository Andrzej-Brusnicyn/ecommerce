<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Services\AuthService;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected AuthService $authService;

    /**
     * OrderController constructor.
     *
     * @param OrderService $orderService
     * @param AuthService $authService
     */
    public function __construct(OrderService $orderService, AuthService $authService)
    {
        $this->orderService = $orderService;
        $this->authService = $authService;
    }

    /**
     * Create a new order.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function createOrder(): JsonResponse|RedirectResponse
    {
        $userId = $this->authService->getUserId();

        if ($userId === null) {
            return redirect()->route('login')->with('message', 'Please log in to create an order.');
        }

        $this->orderService->createOrder((int)$userId);

        return redirect()->route('products.index');
    }
}

