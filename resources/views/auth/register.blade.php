@extends('shared.layout')

@section('content')
    <section id="register" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Register</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                            @error('name')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            @error('email')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                            @error('password_confirmation')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="role">Role</label>
                            <select name="role" class="form-select" id="role" required>
                                <option value="customer">Customer</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        {{-- <div class="form-group mb-3" id="accessCodeField" style="display: none;">
                            <label for="access_code">Access Code (Required for Staff/Admin)</label>
                            <input type="text" id="access_code" name="access_code" class="form-control">
                        </div> --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-dark">Register</button>
                        </div>
                    </form>
                    <hr>
                    <p class="text-center">Already registered? <a href="{{ route('login') }}">Login here</a></p>
                </div>
            </div>
        </div>
    </section>
    {{-- 
    <script>
        document.getElementById('role').addEventListener('change', function() {
            var accessCodeField = document.getElementById('accessCodeField');
            if (this.value === 'staff' || this.value === 'admin') {
                accessCodeField.style.display = 'block';
            } else {
                accessCodeField.style.display = 'none';
            }
        });
    </script> --}}
@endsection
