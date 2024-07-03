@extends('shared.layout')

@section('content')
    <section id="bookings" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Book a Service</h2>
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="service" class="form-label">Service</label>
                            <select class="form-select" id="service" name="service_id" required>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->serviceName.": £".$service->cost }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" class="form-control" id="time" name="time" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-dark">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Include error/success message before button based on validation, i.e., @error(). Laravel automatically generates error message based on validation, see BookingController or Laravel Validation documentation for more information --}}

{{-- @csrf (cross-site request forgery): Blade directive, includes csrf token to prevent csrf attack --}}
