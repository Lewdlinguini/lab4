<?php

// app/Http/Controllers/StripeController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $cart = session()->get('cart', []);

        // Prepare line_items for Stripe Checkout session
        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['product_name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Stripe expects price in cents
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Create checkout session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        // Redirect to Stripe Checkout page
        return redirect($session->url);
    }

    public function success()
{
    $cart = session()->get('cart', []);
    $user = auth()->user();
    $totalAmount = 0;

    if (!empty($cart)) {
        DB::transaction(function () use ($cart, $user, &$totalAmount) {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'payment_status' => 'Completed',
                'shipping_status' => 'Pending',
            ]);

            // Add items to the order
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

            // Update stock for products
            foreach ($cart as $itemId => $item) {
                $product = Product::find($itemId);
                if ($product) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }

            // Update order total amount
            $order->update(['total_amount' => $totalAmount]);

            // Clear cart
            session()->forget('cart');
        });
    }

    return view('checkout.success')->with('success', 'Your order was successful, and stock levels have been updated.');
}

} 

