<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
        //
    }


    public function getCart(Request $request)
    {
        $cart = $this->cartService->getCart($request->user());
        return response()->json($cart);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->addToCart($request->user(), $request->product_id, $request->quantity);
        return response()->json($cart);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = $this->cartService->removeFromCart($request->user(), $request->product_id);
        return response()->json($cart);
    }


    public function clearCart(Request $request)
    {
        $cart = $this->cartService->clearCart($request->user());
        return response()->json($cart);
    }

    
}
