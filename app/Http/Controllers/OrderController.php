<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function createOrder(Request $request)
    {
        $userId = auth()->id();
        $result = $this->orderService->createOrder($userId);

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 400);
        }

        return redirect()->route('catalog')->with('message', $result['message']);
    }
}
