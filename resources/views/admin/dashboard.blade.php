@extends('shared.layout')

@section('content')
    <section id="adminDashboard" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Admin Dashboard</h2>
                    <div class="text-center">
                        <a href="{{ route('admin.createUserForm') }}" class="btn btn-dark">Create New User</a>
                    </div>

                    {{-- Display success message --}}
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
