@extends('shared.layout')

@section('content')
    <section id="bookings" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Book a Service</h2>
                    <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="vehicle_id" class="form-label">Vehicle</label>
                            <select class="form-select" id="vehicle" name="vehicle_id" required
                                onchange="toggleNewVehicleFields()">
                                <option value="">Select Vehicle</option>
                                @if ($vehicles->isEmpty())
                                    <option value="new">Add New Vehicle</option>
                                @else
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->make }} {{ $vehicle->model }}
                                            ({{ $vehicle->licensePlate }})</option>
                                    @endforeach
                                    <option value="new">Add New Vehicle</option>
                                @endif
                            </select>
                            @error('vehicle_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="newVehicleFields" style="display: none;">
                            <div class="mb-3">
                                <label for="make" class="form-label">Make</label>
                                <input type="text" class="form-control" id="make" name="make">
                                @error('make')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="model" class="form-label">Model</label>
                                <input type="text" class="form-control" id="model" name="model">
                                @error('model')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="year" name="year">
                                @error('year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="licensePlate" class="form-label">License Plate</label>
                                <input type="text" class="form-control" id="licensePlate" name="licensePlate">
                                @error('licensePlate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="service_ids" class="form-label">Services</label>
                            @foreach ($services as $service)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="service_{{ $service->id }}"
                                        name="service_ids[]" value="{{ $service->id }}"
                                        data-duration="{{ $service->duration }}">
                                    <label class="form-check-label" for="service_{{ $service->id }}">
                                        {{ $service->serviceName }}: £{{ $service->cost }}
                                    </label>
                                </div>
                            @endforeach
                            @error('service_ids')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Time</label>
                            <select class="form-select" id="time" name="time" required>
                                @foreach ($timeSlots as $slot)
                                    <option value="{{ $slot }}">{{ $slot }}</option>
                                @endforeach
                            </select>
                            @error('time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn custom-btn">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/create_booking.js') }}"></script>
@endsection
