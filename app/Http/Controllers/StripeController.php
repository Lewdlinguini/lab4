<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $cart = session()->get('cart', []);

        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['product_name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Price in cents
                ],
                'quantity' => $item['quantity'],
            ];
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        $cart = session()->get('cart', []);
        $user = auth()->user();
        $totalAmount = 0;

        if (!empty($cart)) {
            DB::transaction(function () use ($cart, $user, &$totalAmount) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'payment_status' => 'Completed',
                    'shipping_status' => 'Pending',
                ]);

                foreach ($cart as $item) {
                    $totalAmount += $item['price'] * $item['quantity'];
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                foreach ($cart as $itemId => $item) {
                    $product = Product::find($itemId);
                    if ($product) {
                        $product->stock -= $item['quantity'];
                        $product->save();
                    }
                }

                $order->update(['total_amount' => $totalAmount]);

                Mail::to($user->email)->send(new OrderConfirmation($order));

                session()->forget('cart');
            });
        }

        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
