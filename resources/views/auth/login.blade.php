@extends('shared.layout')

@section('content')
    <section id="login" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Login</h2>

                    @if (session('status'))
                        <div class="alert alert-success text-center">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" :value="old('email')"
                                required autofocus autocomplete="username">
                            @error('email')
                                <span class="d-block fs-6 text-danger mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required
                                autocomplete="current-password">
                            @error('password')
                                <span class="d-block fs-6 text-danger mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn custom-btn">Log in</button>
                        </div>
                    </form>

                    <hr>

                    @if (Route::has('password.request'))
                        <p class="text-center">
                            <a class="text-decoration-none" href="{{ route('password.request') }}">Forgot your password?</a>
                        </p>
                    @endif

                    <p class="text-center">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
