@extends('shared.layout')

@section('content')
    <section id="login" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Login</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
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
                        <div class="btn-md text-center">
                            <button type="submit" class="btn btn-dark">Login</button>
                        </div>
                    </form>
                    <hr>
                    <p class="text-center">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
