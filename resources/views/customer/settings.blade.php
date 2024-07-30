@extends('shared.layout')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Account Settings</h2>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">Update Email</div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.updateEmail') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email">New Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn custom-btn">Update Email</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Update Phone Number</div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.updatePhone') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="phone">New Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn custom-btn">Update Phone Number</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Update Password</div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.updatePassword') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn custom-btn">Update Password</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Delete Account</div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.destroyAccount') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">Delete
                        Account</button>
                </form>
            </div>
        </div>
    </div>
@endsection
