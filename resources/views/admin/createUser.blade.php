@extends('shared.layout')

@section('content')
    <div class="container py-4">
        <h1>Create New User</h1>
        <form method="POST" action="{{ route('admin.createUser') }}">
            @csrf
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="role">Role</label>
                <select name="role" class="form-select" required>
                    <option value="customer">Customer</option>
                    <option value="employee">Employee</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-dark">Create User</button>
            </div>
        </form>
    </div>
@endsection
