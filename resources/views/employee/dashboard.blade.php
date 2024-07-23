@extends('shared.layout')

@section('content')
    <section id="employeeDashboard" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="mb-4 text-center">Employee Dashboard</h2>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">Bookings</h4>
                        </div>
                        <div class="card-body table-responsive">
                            @if ($bookings->count() > 0)
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Service(s)</th>
                                            <th>Date/Time</th>
                                            <th>Vehicle</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $booking)
                                            <tr data-booking-id="{{ $booking->id }}">
                                                <td>{{ implode(', ', $booking->services->pluck('serviceName')->toArray()) }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($booking->startTime)->format('Y-m-d @ H:i') }}
                                                </td>
                                                <td>{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</td>
                                                <td>
                                                    <select class="form-select status-select" data-id="{{ $booking->id }}">
                                                        <option value="scheduled"
                                                            @if ($booking->status == 'scheduled') selected @endif>Scheduled
                                                        </option>
                                                        <option value="completed"
                                                            @if ($booking->status == 'completed') selected @endif>Completed
                                                        </option>
                                                    </select>
                                                </td>
                                                <td class="text-end">
                                                    @if ($booking->status == 'scheduled')
                                                        <button class="btn btn-sm btn-primary btn-edit-services"
                                                            data-id="{{ $booking->id }}">Edit Services</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="d-flex justify-content-center">
                                    <div class="alert alert-warning text-center">
                                        No bookings found.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include the Edit Services Modal -->
        @include('employee.edit_services_modal')

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle status change
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function() {
                    const bookingId = this.getAttribute('data-id');
                    const status = this.value;

                    fetch(`/employee/bookings/${bookingId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            alert('Status updated successfully.');
                            const row = document.querySelector(
                                `tr[data-booking-id="${bookingId}"]`);
                            const actionCell = row.querySelector('td.text-end');

                            if (status === 'scheduled') {
                                // Add Edit Services button if not exists
                                if (!actionCell.querySelector('.btn-edit-services')) {
                                    const editButton = document.createElement('button');
                                    editButton.className =
                                        'btn btn-sm btn-primary btn-edit-services';
                                    editButton.setAttribute('data-id', bookingId);
                                    editButton.textContent = 'Edit Services';
                                    editButton.addEventListener('click', function() {
                                        currentBookingId = bookingId;
                                        fetch(
                                                `/employee/bookings/${bookingId}/services`)
                                            .then(response => response.text())
                                            .then(html => {
                                                document.getElementById(
                                                        'editServicesModalBody')
                                                    .innerHTML = html;
                                                document.getElementById(
                                                        'edit-services-form')
                                                    .setAttribute(
                                                        'data-booking-id',
                                                        bookingId);
                                                const editServicesModal =
                                                    new bootstrap.Modal(document
                                                        .getElementById(
                                                            'editServicesModal')
                                                        );
                                                editServicesModal.show();
                                            });
                                    });
                                    actionCell.appendChild(editButton);
                                }
                            } else {
                                // Remove Edit Services button if exists
                                const editButton = actionCell.querySelector(
                                    '.btn-edit-services');
                                if (editButton) {
                                    editButton.remove();
                                }
                            }
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                });
            });

            // Handle Edit Services button click
            document.querySelectorAll('.btn-edit-services').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-id');
                    currentBookingId = bookingId; // Store the booking ID in the variable
                    // Load the edit services modal
                    fetch(`/employee/bookings/${bookingId}/services`)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('editServicesModalBody').innerHTML = html;
                            document.getElementById('edit-services-form').setAttribute(
                                'data-booking-id', bookingId); // Set the booking ID on the form
                            const editServicesModal = new bootstrap.Modal(document
                                .getElementById('editServicesModal'));
                            editServicesModal.show();
                        });
                });
            });

            // Handle form submission
            document.getElementById('edit-services-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-booking-id');
                const formData = new FormData(this);

                fetch(`/employee/bookings/${bookingId}/services`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Service update email sent to customer for approval.');
                            const editServicesModal = bootstrap.Modal.getInstance(document
                                .getElementById('editServicesModal'));
                            editServicesModal.hide();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
@endsection
