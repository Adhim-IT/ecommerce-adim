<?php

namespace App\Services;


use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;

class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected CartService $cartService) {}


    public function createOrder(User $user)
    {
        $cart = $this->cartService->getCart($user);
        if (!$cart || $cart->cartitems->isEmpty()) {
            return null;
        }

        return DB::transaction(function () use ($user, $cart) {
            $order = $user->orders()->create([
                'total_amount' => $cart->cartitems->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                }),
            ]);

            foreach ($cart->cartitems as $item) {
                $product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->quantity * $item->product->price,
                ]);
            }
            $this->cartService->clearCart($user);

            return $order;
        });
    }

    public function getUserOrders(User $user)
    {
        return $user->orders()->with('orderItems.product')->get();
    }

    public function updateOrderStatus(Order $order, $status)
    {
       return $order->update(['status' => $status]);
    }
}
