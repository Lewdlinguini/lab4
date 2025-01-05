<?php

namespace App\Http\Controllers;

use App\Notifications\OrderStatusUpdated;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Admin: View all orders with optional filtering by shipping status
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems');

        // Filter orders by shipping status if it's provided in the request
        if ($request->has('shipping_status') && $request->shipping_status != '') {
            $query->where('shipping_status', $request->shipping_status);
        }

        // Get the orders
        $orders = $query->get();

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

        $order->user->notify(new OrderStatusUpdated($order));
    
        // Redirect back with a success message
        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }    

    // Show specific order details
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function userOrders()
    {
        // Get orders related to the authenticated user
        $orders = Order::with('orderItems.product')
                       ->where('user_id', auth()->user()->id)
                       ->get();

        return view('orders.index', compact('orders'));
    }

    // Store an order
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
