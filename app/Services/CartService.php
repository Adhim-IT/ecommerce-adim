<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;

class CartService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function getCart(User $user)
    {
        return Cart::with('cartitems.product')
        ->where('user_id', $user->id)
        ->first();
    }


    public function addToCart(User $user, $productId, $quantity)
    {
        $product = \App\Models\Product::find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        if ($quantity < 1) {
            return response()->json(['error' => 'Quantity must be at least 1'], 400);
        }
        if ($quantity > $product->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        $cart = $this->getCart($user);
        $cartItem = $cart->cartitems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cart->cartitems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cart->load('cartitems.product');
    }


    public function removeFromCart(User $user, $productId)
    {
        $cart = $this->getCart($user);
        $cartItem = $cart->cartitems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return $cart->load('cartitems.product');
    }


    public function clearCart(User $user)
    {
        $cart = $this->getCart($user);
        $cart->cartitems()->delete();
        return $cart->load('cartitems.product');
    }
}
