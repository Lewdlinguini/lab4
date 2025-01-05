<?php

namespace App\Http\Controllers;

use App\Notifications\OrderStatusUpdated;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems');

       
        if ($request->has('shipping_status') && $request->shipping_status != '') {
            $query->where('shipping_status', $request->shipping_status);
        }

      
        $orders = $query->get();

        return view('admin.orders.index', compact('orders'));
    }

   
    public function updateStatus(Request $request, Order $order)
    {
        
        $request->validate([
            'shipping_status' => 'required|string|in:Pending,Shipped,Delivered',
            'payment_status' => 'required|string|in:Pending,Completed',
        ]);
    
        
        $order->update([
            'shipping_status' => $request->shipping_status,
            'payment_status' => $request->payment_status,
        ]);

        $order->user->notify(new OrderStatusUpdated($order));
    
       
        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }    

   
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function userOrders()
    {
       
        $orders = Order::with('orderItems.product')
                       ->where('user_id', auth()->user()->id)
                       ->get();

        return view('orders.index', compact('orders'));
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'shipping_address' => 'required|string',
            'products' => 'required|array', 
        ]);

       
        $order = new Order();
        $order->user_id = auth()->user()->id; 
        $order->shipping_address = $request->shipping_address;
        $order->save();

        
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']); 
            
            if ($product) {
              
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $productData['quantity'];
                $orderItem->price = $product->price; 
                $orderItem->product_name = $product->product_name; 
                $orderItem->save();
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order placed successfully!');
    }
}
