<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Handle payment success and update order status
    public function paymentSuccess($order_id)
    {
        // Find the order by ID
        $order = Order::findOrFail($order_id);
        
        // Update payment status to 'paid'
        $order->payment_status = 'paid';
        $order->save();

        // Redirect to product index with success message
        return redirect()->route('products.index')->with('success', 'Payment successful!');
    }

    // Handle payment cancellation
    public function paymentCancel()
    {
        // Redirect to product index with error message
        return redirect()->route('products.index')->with('error', 'Payment canceled.');
    }

    // Create a new order (this method could be triggered before the payment process)
    public function createOrder(Request $request)
    {
        // Assuming you have a shopping cart or some method to calculate the total
        $totalAmount = $request->input('total_amount');  // You should calculate the total amount based on the cart

        // Create a new order with 'pending' payment and shipping status
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,  // Calculate total amount based on cart or input
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
        ]);

        // Return order confirmation or further actions (e.g., redirect to payment page)
        return redirect()->route('payment.page', ['order_id' => $order->id]);
    }
}
