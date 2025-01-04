<?php

// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerDetail;

class CheckoutController extends Controller
{
    public function showCheckoutForm()
    {
        return view('checkout.checkout'); // Adjust if needed based on your view structure
    }

    public function storeCheckoutData(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);
    
        // Check if the customer already exists based on the email
        $customer = \App\Models\CustomerDetail::updateOrCreate(
            ['email' => $validatedData['email']], // Find existing customer by email
            [
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'payment_method' => $validatedData['payment_method'],
            ]
        );
    
        // Redirect to Stripe payment gateway or success page based on payment method
        if ($validatedData['payment_method'] === 'stripe') {
            // Redirect to Stripe payment (you can replace this with the appropriate Stripe redirect)
            return redirect()->route('checkout.stripe');
        }
    
        // If the payment method is not Stripe, go to the success page
        return redirect()->route('checkout.success');
    }    

}