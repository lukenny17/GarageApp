@extends('shared.layout')

@section('content')
    <section id="addService" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Add New Service</h2>
                    <form action="{{ route('admin.storeService') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="serviceName" class="form-label">Service Name</label>
                            <input type="text" id="serviceName" name="serviceName" class="form-control" required>
                            @error('serviceName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cost" class="form-label">Cost</label>
                            <input type="number" step="0.01" id="cost" name="cost" class="form-control"
                                required>
                            @error('cost')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (hours)</label>
                            <input type="number" step="0.1" id="duration" name="duration" class="form-control"
                                required>
                            @error('duration')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn custom-btn">Add Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
