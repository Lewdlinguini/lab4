@extends('layouts.app')

@section('content')
<div class="checkout-container">
    <h2 class="text-center mb-4">Checkout</h2>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

        <!-- Visible Full Name (Disabled for editing) -->
        <div class="form-group mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" id="name" 
                   value="{{ old('name', auth()->user()->name) }}" required disabled>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Visible Email (Disabled for editing) -->
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" 
                   value="{{ old('email', auth()->user()->email) }}" required disabled>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Hidden Fields to Submit Name and Email -->
        <input type="hidden" name="name" value="{{ auth()->user()->name }}">
        <input type="hidden" name="email" value="{{ auth()->user()->email }}">

        <!-- Shipping Address Field -->
        <div class="form-group mb-3">
            <label for="address" class="form-label">Shipping Address</label>
            <textarea name="address" class="form-control" id="address" required>{{ old('address') }}</textarea>
            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Payment Method Field -->
        <div class="form-group mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" id="payment_method" required>
                <option value="stripe">Stripe</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="form-group mb-3">
             <button type="submit" class="btn btn-success">Pay with Stripe</button>
        </div>
    </form>
</div>
@endsection
