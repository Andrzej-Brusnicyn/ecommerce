<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Services\AuthService;
use App\Repositories\OrderRepositoryInterface;

class OrderController extends Controller
{
    protected OrderRepositoryInterface $orderRepository;
    protected AuthService $authService;

    /**
     * OrderController constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param AuthService $authService
     */
    public function __construct(OrderRepositoryInterface $orderRepository, AuthService $authService)
    {
        $this->orderRepository = $orderRepository;
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

        try {
            $this->orderRepository->createOrder($userId);

            return redirect()->route('products.index');
        } catch (\Exception $e) {

            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }
}

