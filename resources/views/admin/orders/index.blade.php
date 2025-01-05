@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4 text-center">Orders</h1>

        <!-- Filter Form (Visible only to Admin users) -->
        @if(auth()->check() && auth()->user()->role->name === 'Admin')
            <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="shipping_status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ request('shipping_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Shipped" {{ request('shipping_status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ request('shipping_status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                </div>
            </form>
        @endif

        @if(session('success'))
            <!-- Success Message Modal -->
            <script>
                window.onload = function() {
                    var message = @json(session('success'));
                    if (message) {
                        showSuccessModal(message);
                    }
                }

                function showSuccessModal(message) {
                    document.getElementById('successMessage').textContent = message;
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                }
            </script>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @if(auth()->user()->role->name === 'Admin' || auth()->user()->id === $order->user_id)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $order->payment_status }}</span> /
                                    <span class="badge bg-secondary">{{ $order->shipping_status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>

                                    <!-- Only show the Update Status button if the user is an Admin -->
                                    @if(auth()->check() && auth()->user()->role->name === 'Admin')
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $order->id }}">
                                            Update Status
                                        </button>
                                    @endif

                                    <!-- Modal for Updating Status -->
                                    <div class="modal fade" id="updateStatusModal{{ $order->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateStatusModalLabel">Update Order Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="mb-3">
                                                            <label for="shipping_status" class="form-label">Shipping Status</label>
                                                            <select name="shipping_status" id="shipping_status" class="form-select">
                                                                <option value="Pending" {{ $order->shipping_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="Shipped" {{ $order->shipping_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                                                <option value="Delivered" {{ $order->shipping_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="payment_status" class="form-label">Payment Status</label>
                                                            <select name="payment_status" id="payment_status" class="form-select">
                                                                <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="Completed" {{ $order->payment_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                            </select>
                                                        </div>

                                                        <button type="submit" class="btn btn-warning">Update Status</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Success Notification Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
