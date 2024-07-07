@extends('shared.layout')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4 text-center">Customer Dashboard</h2>

        <!-- Bookings Section -->
        <div class="mb-4">
            <h4 class="text-center">Bookings</h4>
            @if ($bookings->count() > 0)
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Scheduled</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->service->serviceName }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->startTime)->format('Y-m-d @ H:i') }}</td>
                                <td>{{ $booking->status }}</td>
                                <td class="text-end">
                                    @if ($booking->status !== 'completed')
                                        <button class="btn btn-sm btn-primary btn-reschedule-booking"
                                            data-id="{{ $booking->id }}">Reschedule</button>
                                        <button class="btn btn-sm btn-danger btn-cancel-booking"
                                            data-id="{{ $booking->id }}">Cancel</button>
                                    @else
                                        <button class="btn btn-sm btn-success btn-review-booking"
                                            data-id="{{ $booking->id }}">Leave Review</button>
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

        <!-- Vehicles Section -->
        <div class="mb-4">
            <h4 class="text-center">Vehicles</h4>
            @if ($vehicles->count() > 0)
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th>Make</th>
                            <th>Model</th>
                            <th>License Plate</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicles as $vehicle)
                            <tr>
                                <td> {{ $vehicle->make }} </td>
                                <td> {{ $vehicle->model }} </td>
                                <td>{{ $vehicle->licensePlate }}</td>
                                <td class="text-end"><button class="btn btn-sm btn-warning btn-edit-vehicle"
                                        data-id="{{ $vehicle->id }}">Edit</button></td>
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

        <!-- Modals -->
        <!-- Reschedule Booking Modal -->
        <div class="modal fade" id="rescheduleBookingModal" tabindex="-1" aria-labelledby="rescheduleBookingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="reschedule-booking-form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rescheduleBookingModalLabel">Reschedule Booking</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="reschedule-date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="reschedule-date" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="reschedule-time" class="form-label">Time</label>
                                <input type="time" class="form-control" id="reschedule-time" name="time" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Review Booking Modal -->
        <div class="modal fade" id="reviewBookingModal" tabindex="-1" aria-labelledby="reviewBookingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="review-booking-form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reviewBookingModalLabel">Leave a Review</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <select class="form-select" id="rating" name="rating" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Vehicle Modal -->
        <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="edit-vehicle-form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editVehicleModalLabel">Edit Vehicle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="make" class="form-label">Make</label>
                                <input type="text" class="form-control" id="make" name="make" required>
                            </div>
                            <div class="mb-3">
                                <label for="model" class="form-label">Model</label>
                                <input type="text" class="form-control" id="model" name="model" required>
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="year" name="year" required>
                            </div>
                            <div class="mb-3">
                                <label for="licensePlate" class="form-label">License Plate</label>
                                <input type="text" class="form-control" id="licensePlate" name="licensePlate"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cancel Booking
            document.querySelectorAll('.btn-cancel-booking').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-id');
                    fetch(`/customer/bookings/cancel/${bookingId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                });
            });

            // Reschedule Booking
            document.querySelectorAll('.btn-reschedule-booking').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-id');
                    document.getElementById('reschedule-booking-form').setAttribute('data-id',
                        bookingId);
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
                        new bootstrap.Modal(document.getElementById('rescheduleBookingModal')).hide();
                        location.reload();
                    }
                });
            });

            // Leave Review
            document.querySelectorAll('.btn-review-booking').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-id');
                    document.getElementById('review-booking-form').setAttribute('data-id',
                        bookingId);
                    new bootstrap.Modal(document.getElementById('reviewBookingModal')).show();
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
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        new bootstrap.Modal(document.getElementById('reviewBookingModal')).hide();
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
        });
    </script>
@endsection
