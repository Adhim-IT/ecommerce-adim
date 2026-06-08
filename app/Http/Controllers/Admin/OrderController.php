<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    Public function index()
    {
        $orders = Order::paginate(10);
        $orders->load('user');
        return response()->json($orders);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $order->load('user', 'orderitems.product');
        return response()->json($order);
    }
    
}
