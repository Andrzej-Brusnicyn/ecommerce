<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    protected OrderService $orderService;

    /**
     * OrderController constructor.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Create a new order.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function createOrder(): JsonResponse|RedirectResponse
    {
        $userId = auth()->id();
        $result = $this->orderService->createOrder($userId);

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 400);
        }

        return redirect()->route('products.index')->with('message', $result['message']);
    }
}
