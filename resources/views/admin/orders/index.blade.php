@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Orders</h1>

        @if(session('success'))
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
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Shipping Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user ? $order->user->name : 'N/A' }}</td>
                            <td>{{ $order->total_amount }}</td>
                            <td>{{ ucfirst($order->payment_status) }}</td>
                            <td>{{ ucfirst($order->shipping_status) }}</td>
                            <td class="d-flex justify-content-start">
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm mx-2 d-flex align-items-center">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm mx-2 d-flex align-items-center" onclick="showDeleteModal('{{ route('orders.destroy', $order->id) }}', '{{ $order->id }}')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No orders found.</td>
                        </tr>
                    @endforelse
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the order with ID <strong id="orderID"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            function showDeleteModal(actionUrl, orderID) {
                document.getElementById('deleteForm').action = actionUrl;
                document.getElementById('orderID').textContent = orderID;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            }
        </script>
    @endsection
@endsection
