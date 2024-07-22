@extends('shared.layout')

@section('content')
    <section id="customerDashboard" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="mb-4 text-center">Customer Dashboard</h2>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <!-- Bookings Section -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="mb-0">Bookings</h4>
                                </div>
                                <div class="card-body table-responsive">
                                    @if ($bookings->count() > 0)
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 35%;">Service(s)</th>
                                                    <th style="width: 15%;">Date/Time</th>
                                                    <th style="width: 12.5%;">Vehicle</th>
                                                    <th style="width: 12.5%;">Status</th>
                                                    <th style="width: 25%;" class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bookings as $booking)
                                                    <tr>
                                                        <td>
                                                            {{ implode(', ', $booking->services->pluck('serviceName')->toArray()) }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($booking->startTime)->format('Y-m-d @ H:i') }}
                                                        </td>
                                                        <td>{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}
                                                        </td>
                                                        <td>{{ $booking->status }}</td>
                                                        <td class="text-end">
                                                            @if ($booking->status !== 'completed')
                                                                <button
                                                                    class="btn btn-sm btn-primary btn-reschedule-booking"
                                                                    data-id="{{ $booking->id }}"
                                                                    data-duration="{{ $booking->duration }}">Reschedule</button>
                                                                <button class="btn btn-sm btn-danger btn-cancel-booking"
                                                                    data-id="{{ $booking->id }}">Cancel</button>
                                                            @else
                                                                <button class="btn btn-sm btn-success btn-review-booking"
                                                                    data-id="{{ $booking->id }}"
                                                                    @if ($booking->review_submitted) disabled @endif>
                                                                    @if ($booking->review_submitted)
                                                                        Review Submitted
                                                                    @else
                                                                        Leave Review
                                                                    @endif
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-sm btn-secondary"
                                                                onclick="location.href='mailto:admin@example.com?subject=Query About Booking #{{ $booking->id }}'">Contact</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <div class="alert alert-warning text-center">
                                                {{ Auth::user()->name }} has no bookings.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <!-- Vehicles Section -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Vehicles</h4>
                                </div>
                                <div class="card-body table-responsive">
                                    @if ($vehicles->count() > 0)
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%;">Make</th>
                                                    <th style="width: 25%;">Model</th>
                                                    <th style="width: 25%;">License Plate</th>
                                                    <th style="width: 25%;" class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vehicles as $vehicle)
                                                    <tr>
                                                        <td> {{ $vehicle->make }} </td>
                                                        <td> {{ $vehicle->model }} </td>
                                                        <td>{{ $vehicle->licensePlate }}</td>
                                                        <td class="text-end">
                                                            <button class="btn btn-sm btn-warning btn-edit-vehicle"
                                                                data-id="{{ $vehicle->id }}">Edit</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <div class="alert alert-warning text-center">
                                                {{ Auth::user()->name }} has no vehicles registered with this account.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Link -->
                    <div class="mb-4 text-center">
                        <a href="{{ route('customer.settings') }}" class="btn custom-btn">
                            <span>Edit Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        @include('customer.reschedule_modal')
        @include('customer.review_modal')
        @include('customer.edit_vehicle_modal')
        @include('customer.cancel_confirmation_modal')

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let cancelBookingId = null;

            // Cancel Booking
            document.querySelectorAll('.btn-cancel-booking').forEach(button => {
                button.addEventListener('click', function() {
                    cancelBookingId = this.getAttribute('data-id');
                    const cancelModal = new bootstrap.Modal(document.getElementById(
                        'cancelConfirmationModal'));
                    cancelModal.show();
                });
            });

            document.getElementById('confirmCancelBooking').addEventListener('click', function() {
                if (cancelBookingId) {
                    fetch(`/customer/bookings/cancel/${cancelBookingId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            });

            // Reschedule Booking
            document.querySelectorAll('.btn-reschedule-booking').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-id');
                    const bookingDuration = this.getAttribute('data-duration');
                    document.getElementById('reschedule-booking-form').setAttribute('data-id',
                        bookingId);
                    document.getElementById('reschedule-booking-form').setAttribute('data-duration',
                        bookingDuration);
                    updateRescheduleTimes(bookingDuration);
                    new bootstrap.Modal(document.getElementById('rescheduleBookingModal')).show();
                });
            });

            document.getElementById('reschedule-booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-id');
                const formData = new FormData(this);
                fetch(`/customer/bookings/reschedule/${bookingId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        new bootstrap.Modal(document.getElementById('rescheduleBookingModal'))
                            .hide();
                        location.reload();
                    }
                });
            });

            // Star rating system
            const stars = document.querySelectorAll('.star-rating .fa-star');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;
                    stars.forEach(s => {
                        if (s.getAttribute('data-value') <= value) {
                            s.classList.add('checked');
                        } else {
                            s.classList.remove('checked');
                        }
                    });
                });
            });

            // Leave Review
            document.querySelectorAll('.btn-review-booking').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-id');
                    document.getElementById('review-booking-form').setAttribute('data-id',
                        bookingId);
                    const reviewModal = new bootstrap.Modal(document.getElementById(
                        'reviewBookingModal'));
                    reviewModal.show();
                });
            });

            document.getElementById('review-booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-id');
                const formData = new FormData(this);

                fetch(`/customer/bookings/review/${bookingId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close the modal
                            const reviewModal = bootstrap.Modal.getInstance(document.getElementById(
                                'reviewBookingModal'));
                            reviewModal.hide();
                            location.reload();
                        }
                    });
            });

            // Edit Vehicle
            document.querySelectorAll('.btn-edit-vehicle').forEach(button => {
                button.addEventListener('click', function() {
                    const vehicleId = this.getAttribute('data-id');
                    fetch(`/customer/vehicles/${vehicleId}`).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const vehicle = data.vehicle;
                                document.getElementById('make').value = vehicle.make;
                                document.getElementById('model').value = vehicle.model;
                                document.getElementById('year').value = vehicle.year;
                                document.getElementById('licensePlate').value = vehicle
                                    .licensePlate;
                                document.getElementById('edit-vehicle-form').setAttribute(
                                    'data-id', vehicleId);
                                new bootstrap.Modal(document.getElementById('editVehicleModal'))
                                    .show();
                            }
                        });
                });
            });

            document.getElementById('edit-vehicle-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const vehicleId = this.getAttribute('data-id');
                const formData = new FormData(this);
                fetch(`/customer/vehicles/update/${vehicleId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        new bootstrap.Modal(document.getElementById('editVehicleModal')).hide();
                        location.reload();
                    }
                });
            });

            // Function to update available reschedule times
            function updateRescheduleTimes(duration) {
                const timeSelect = document.getElementById('reschedule-time');
                const dateInput = document.getElementById('reschedule-date');
                const originalOptions = Array.from(timeSelect.options);

                dateInput.addEventListener('input', function() {
                    const selectedDate = new Date(dateInput.value);
                    const day = selectedDate.getDay();
                    if (day === 0 || day === 6) { // 0 = Sunday, 6 = Saturday
                        alert('Please select a weekday.');
                        dateInput.value = '';
                        return;
                    }

                    const endTime = new Date();
                    endTime.setHours(17, 0, 0, 0); // 5pm

                    timeSelect.innerHTML = '';

                    originalOptions.forEach(function(option) {
                        const startTime = new Date();
                        const [hours, minutes] = option.value.split(':');
                        startTime.setHours(hours, minutes, 0, 0);

                        const endTimeWithService = new Date(startTime.getTime() + duration * 60 *
                            60 * 1000);

                        if (endTimeWithService <= endTime) {
                            timeSelect.add(option.cloneNode(true));
                        }
                    });
                });

                dateInput.dispatchEvent(new Event(
                    'input')); // Trigger the date input event to update times initially
            }
        });
    </script>
@endsection
