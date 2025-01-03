@extends('layouts.app')

@section('content')
    <div class="cart-container">
        <h2 class="text-center mb-4" style="font-family: 'Georgia', serif; font-size: 2.5rem; color: #2c3e50;">🛒</h2>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="cart-items d-grid grid-template-columns gap-4">
                @foreach(session('cart') as $id => $details)
                    @if(isset($details['product_name']) && isset($details['image']) && isset($details['price']) && isset($details['quantity']))
                        <div class="cart-item card shadow-sm mb-3" style="border-radius: 8px; border: 1px solid #e2e2e2; padding: 8px; width: 100%;">
                            <div class="cart-item-info d-flex align-items-center mb-2">
                                <img src="{{ asset('images/' . $details['image']) }}" alt="{{ $details['product_name'] }}" class="cart-item-image rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="cart-item-details ms-2">
                                    <p class="product-name mb-1" style="font-weight: bold; font-size: 14px; line-height: 1.2;">{{ $details['product_name'] }}</p>
                                    <p class="product-price text-muted mb-0" style="font-size: 12px;">${{ number_format($details['price'], 2) }}</p>
                                </div>
                            </div>

                            <div class="quantity-controls d-flex align-items-center justify-content-center gap-3 py-1">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="quantity-form">
                                    @csrf
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="btn btn-danger btn-circle quantity-button">-</button>
                                </form>

                                <span class="quantity" style="font-weight: bold; font-size: 16px; line-height: 1;">{{ $details['quantity'] }}</span>

                                <form action="{{ route('cart.update', $id) }}" method="POST" class="quantity-form">
                                    @csrf
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="btn btn-success btn-circle quantity-button">+</button>
                                </form>
                            </div>

                            <div class="cart-item-total d-flex justify-content-between align-items-center mt-2">
                                <p class="total-price fw-bold" style="font-size: 14px;">${{ number_format($details['price'] * $details['quantity'], 2) }}</p>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="remove-item-form">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="cart-summary mt-3 text-end">
                <p class="fw-bold" style="font-size: 16px;">Total: ${{ number_format(array_sum(array_map(function ($item) {
                    return $item['price'] * $item['quantity'];
                }, session('cart'))), 2) }}</p>

              
                <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>


            </div>
        @else
            <p class="text-center">Your cart is empty.</p>
        @endif
    </div>
@endsection

<style>
    .cart-items {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 items per row */
        gap: 20px;
    }

    .cart-item {
        width: 100%; /* Ensure each item takes full width in the grid cell */
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .quantity-button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 0.2s;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        padding: 0;
    }

    .quantity-button:hover {
        transform: scale(1.1);
    }

    .btn-danger.quantity-button {
        background-color: #e74c3c;
        color: white;
    }

    .btn-danger.quantity-button:hover {
        background-color: #c0392b;
    }

    .btn-success.quantity-button {
        background-color: #2ecc71;
        color: white;
    }

    .btn-success.quantity-button:hover {
        background-color: #27ae60;
    }

    .quantity {
        font-weight: bold;
        font-size: 20px;
        line-height: 5;
    }

    @media (max-width: 768px) {
        .cart-items {
            grid-template-columns: repeat(2, 1fr); /* 2 items per row on smaller screens */
        }
    }

    @media (max-width: 480px) {
        .cart-items {
            grid-template-columns: 1fr; /* 1 item per row on extra small screens */
        }
    }
</style>
