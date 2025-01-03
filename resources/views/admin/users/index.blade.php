@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Users</h1>

        @if(session('success'))
            <!-- Trigger the modal to show the success message -->
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

        <!-- Create New User Button aligned to the left -->
        <div class="d-flex justify-content-start mb-4">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                <i class="bi bi-person-plus fs-4 me-2"></i> Create New User
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td class="d-flex justify-content-start">
                                <!-- Edit Button with Icon -->
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm mx-2 d-flex align-items-center">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <!-- Delete Button with Icon -->
                                <button type="button" class="btn btn-danger btn-sm mx-2 d-flex align-items-center" onclick="showDeleteModal('{{ route('users.destroy', $user->id) }}', '{{ $user->name }}')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the user <strong id="userName"></strong>?
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
            function showDeleteModal(actionUrl, userName) {
                document.getElementById('deleteForm').action = actionUrl;
                document.getElementById('userName').textContent = userName;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            }
        </script>
    @endsection
@endsection
