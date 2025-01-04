<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Admin: View all orders
    public function index()
    {
        $orders = Order::with('user', 'orderItems')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Admin: Update order status
    public function updateStatus(Request $request, Order $order)
    {
        // Validate the input
        $request->validate([
            'shipping_status' => 'required|string|in:Pending,Shipped,Delivered',
            'payment_status' => 'required|string|in:Pending,Completed',
        ]);
    
        // Update the shipping and payment statuses
        $order->update([
            'shipping_status' => $request->shipping_status,
            'payment_status' => $request->payment_status,
        ]);
    
        // Redirect back with a success message
        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }    

    // Show specific order details
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Create a new order (with order items)
  
   // Create a new order (with order items)
public function store(Request $request)
{
    // Validate the order data (add validation rules as necessary)
    $request->validate([
        'shipping_address' => 'required|string',
        'products' => 'required|array', // Assumes products are passed as an array
    ]);

    // Create the order
    $order = new Order();
    $order->user_id = auth()->user()->id; // Assuming user is authenticated
    $order->shipping_address = $request->shipping_address;
    $order->save();

    // Loop through the products passed in the request and add them to the order
    foreach ($request->products as $productData) {
        $product = Product::find($productData['product_id']); // Find the product by ID
        
        if ($product) {
            // Create the order item
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $productData['quantity'];
            $orderItem->price = $product->price; // You can adjust this to use a custom price if needed
            $orderItem->product_name = $product->product_name; // Set product name explicitly
            $orderItem->save();
        }
    }

    return redirect()->route('admin.orders.index')->with('success', 'Order placed successfully!');
}

}
