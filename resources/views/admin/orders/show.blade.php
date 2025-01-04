@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <!-- Changed text color to black for the order header -->       
        <h1 class="display-4 text-dark mb-4">Order Details - Order #{{ $order->id }}</h1>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Order Summary</h5>
                <p class="mb-2"><strong>Customer Name:</strong> {{ $order->user ? $order->user->name : 'Guest' }}</p>
                <p class="mb-2"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p class="mb-2"><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                <p class="mb-2"><strong>Payment Status:</strong> 
                    <span class="badge {{ $order->payment_status == 'Paid' ? 'bg-success' : 'bg-warning' }}">
                        {{ $order->payment_status }}
                    </span>
                </p>
                <p><strong>Shipping Status:</strong> 
                    <span class="badge {{ $order->shipping_status == 'Shipped' ? 'bg-primary' : 'bg-secondary' }}">
                        {{ $order->shipping_status }}
                    </span>
                </p>
            </div>
        </div>

        <h3 class="mb-4">Order Items</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <!-- Ensure product relationship exists and product name is displayed -->
                            <td>{{ $item->product ? $item->product->name : 'Unknown Product' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
@endsection
