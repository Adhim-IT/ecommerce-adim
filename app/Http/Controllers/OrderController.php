<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        //
    }

    public function createOrder(Request $request)
    {
        $order = $this->orderService->createOrder($request->user());
        if (!$order) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        return response()->json($order, 201);
    }

    public function getUserOrders(Request $request)
    {
        $orders = $this->orderService->getUserOrders($request->user());
        return response()->json($orders);
    }

    // public function updateOrderStatus(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'status' => 'required|in:pending,completed,cancelled',
    //     ]);
    //     $updated = $this->orderService->updateOrderStatus($order, $request->status);
    //     return response()->json($updated);
    // }
}
