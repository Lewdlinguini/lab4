<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Thank you for your order!</p>
    <p>Your order ID is: {{ $order->id }}</p>
    <p>Total amount: ${{ number_format($order->total_amount, 2) }}</p>
    
    <h3>Order Details:</h3>
    <ul>
        @foreach($order->orderItems as $item)
            <li>{{ $item->product_name }} (x{{ $item->quantity }}) - ${{ number_format($item->price * $item->quantity, 2) }}</li>
        @endforeach
    </ul>
    
    <p>We will notify you once your order is shipped.</p>
</body>
</html>
