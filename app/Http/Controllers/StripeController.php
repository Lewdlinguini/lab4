<?php

// app/Http/Controllers/StripeController.php
namespace App\Http\Controllers;

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
    
        if (!empty($cart)) {
            DB::transaction(function () use ($cart) {
                foreach ($cart as $itemId => $item) {
                    $product = Product::find($itemId);
                    if ($product) {
                        $product->stock -= $item['quantity'];
                        $product->save();
                    }
                }
    
                session()->forget('cart');
            });
        }
    
        return view('checkout.success')->with('success', 'Your order was successful, and stock levels have been updated.');
    }
    
    public function cancel()
    {
        return view('checkout.cancel');
    }

}