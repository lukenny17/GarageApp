@extends('shared.layout')

@section('content')
    <section id="register" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Customer Registration</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        {{-- Name --}}
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" :value="old('name')"
                                required autofocus autocomplete="name">
                            @error('name')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>

                        {{-- Email Address --}}
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" :value="old('email')"
                                required autocomplete="username">
                            @error('email')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>

                        {{-- Phone Number --}}
                        <div class="form-group mb-3">
                            <label for="phone">Phone Number (Optional)</label>
                            <input type="text" id="phone" name="phone" class="form-control" :value="old('phone')"
                                autocomplete="phone">
                            @error('phone')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required
                                autocomplete="new-password">
                            @error('password')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" required autocomplete="new-password">
                            @error('password_confirmation')
                                <span class="d-block fs-6 text-danger mt-2"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn custom-btn">Register</button>
                        </div>
                    </form>
                    <hr>
                    <p class="text-center">Already registered? <a href="{{ route('login') }}">Login here</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
