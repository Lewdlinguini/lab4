<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerDetail;

class CheckoutController extends Controller
{
    public function showCheckoutForm()
    {
        return view('checkout.checkout'); 
    }

    public function storeCheckoutData(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);
    
       
        $customer = \App\Models\CustomerDetail::updateOrCreate(
            ['email' => $validatedData['email']], 
            [
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'payment_method' => $validatedData['payment_method'],
            ]
        );
    
       
        if ($validatedData['payment_method'] === 'stripe') {
            
            return redirect()->route('checkout.stripe');
        }
    
       
        return redirect()->route('checkout.success');
    }    

}