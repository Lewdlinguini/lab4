<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
      
        $cart = session()->get('cart', []);

      
        $total = null;
        if (!empty($cart)) {
            $total = array_sum(array_map(function ($item) {
                return isset($item['price'], $item['quantity']) ? $item['price'] * $item['quantity'] : 0;
            }, $cart));
        }

       
        return view('cart', compact('cart', 'total'));
    }

    public function addToCart($product_id, $quantity = 1)
    {
        $product = Product::findOrFail($product_id);
        $cart = session()->get('cart', []);

       
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = [
                'product_name' => $product->product_name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);
        return back();
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

      
        if (isset($cart[$id])) {
            if ($request->has('action')) {
               
                if ($request->action === 'increase') {
                    $cart[$id]['quantity']++;
                } elseif ($request->action === 'decrease') {
                    if ($cart[$id]['quantity'] > 1) {
                        $cart[$id]['quantity']--;
                    }
                }
            } else {
              
                $cart[$id]['quantity'] = $request->quantity ?? $cart[$id]['quantity'];
            }

            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }
}
