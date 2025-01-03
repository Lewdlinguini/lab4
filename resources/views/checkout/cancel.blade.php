@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Checkout Canceled</h1>
        <p>We're sorry, but your payment was not successful. You can try again or contact support for assistance.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Back to Products</a>
    </div>
@endsection
