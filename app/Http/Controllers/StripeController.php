<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.5',
        ]);
        $order = Order::find($request->order_id);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => intval($request->amount * 100),
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            $order->stripe_payment_id = $paymentIntent->id;
            $order->save();

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            if ($event->type === 'payment_intent.succeeded') {
                $paymentIntent = $event->data->object;
                $orderId = $paymentIntent->metadata->order_id ?? null;
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $order->status = 'completed';
                        $order->save();
                    }
                }else {
                    \Log::warning('PaymentIntent succeeded without order_id in metadata', ['payment_intent' => $paymentIntent->id]);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }
    
}
