@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-5" style="font-family: 'Georgia', serif; font-size: 2.5rem; color: #2c3e50;">👤 Edit Profile</h2>

    <div class="card shadow-lg p-4 mb-4" style="border-radius: 10px; border: 1px solid #e2e2e2; max-width: 600px; margin: 0 auto;">
        <h5 class="card-title mb-4 font-weight-bold">Edit Your Profile</h5>

        <!-- Profile Edit Form -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name', $user->name) }}" placeholder="Enter your full name">
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                 <label for="email" class="form-label">Email Address</label>
                 <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email', $user->email) }}" readonly>
           </div>

            <div class="mb-4">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter a new password">
                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm your new password">
            </div>

            <div class="mb-4">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control form-control-lg">
                @error('profile_picture') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100" style="font-size: 16px; padding: 10px;">Save Changes</button>
        </form>
    </div>

    <hr class="my-5">

    <!-- Account Deletion Warning -->
    <div class="card bg-danger text-white shadow-lg p-4" style="border-radius: 10px; border: 1px solid #e2e2e2; max-width: 600px; margin: 0 auto;">
        <h6 class="alert-heading mb-4 font-weight-bold">Warning: Account Deletion</h6>
        <p class="small">Deleting your account is permanent and cannot be undone. If you are sure you want to proceed, click the button below.</p>
        <form action="{{ route('profile.delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-light btn-lg w-100" style="font-size: 16px; padding: 10px;">Delete Account</button>
        </form>
    </div>
</div>
@endsection

<style>
    .container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .card {
        border-radius: 10px;
        border: 1px solid #e2e2e2;
        padding: 20px;
        margin-bottom: 30px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #e74c3c;
        border: none;
    }

    .btn-light {
        background-color: #f8f9fa;
        color: #343a40;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-light:hover {
        background-color: #ddd;
    }

    .form-label {
        font-size: 16px;
        font-weight: bold;
    }

    .form-control-lg {
        font-size: 16px;
        height: 45px;
        border-radius: 5px;
    }

    .alert-heading {
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }

        .card {
            padding: 15px;
        }

        .btn-primary, .btn-light {
            font-size: 14px;
        }
    }
</style>
