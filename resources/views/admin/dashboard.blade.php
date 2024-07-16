@extends('shared.layout')

@section('content')
    <section id="adminDashboard" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="mb-4 text-center">Admin Dashboard</h2>
                    <div class="text-center">
                        <a href="{{ route('admin.createUserForm') }}" class="btn btn-dark mb-4">Create New User</a>
                    </div>

                    {{-- Display success message --}}
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="fetchBookingsForm" class="mb-4">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-auto d-flex align-items-center">
                                <label for="startDate" class="me-2">From</label>
                                <input type="date" id="startDate" name="startDate" class="form-control">
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <label for="endDate" class="me-2">To</label>
                                <input type="date" id="endDate" name="endDate" class="form-control">
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <button type="button" id="viewBookingsButton" class="btn btn-dark">View Bookings</button>
                            </div>
                        </div>
                    </form>

                    <div id="errorMessage" class="alert alert-danger mt-3 d-none">
                        An error occurred while fetching bookings.
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Service Type</th>
                                    <th>Date/Time</th>
                                    <th>Status</th>
                                    <th>Employee</th>
                                </tr>
                            </thead>
                            <tbody id="bookingsTableBody">
                                {{-- Bookings will be inserted here --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');

            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            const oneWeekFromToday = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

            startDate.value = today;
            endDate.value = oneWeekFromToday;
        });

        document.getElementById('viewBookingsButton').addEventListener('click', fetchBookings);

        function fetchBookings() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Please select both start and end dates.');
                return;
            }

            const data = {
                start_date: startDate,
                end_date: endDate,
                _token: '{{ csrf_token() }}'
            };

            console.log('Fetching bookings');
            fetch('{{ route('admin.getBookings') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.bookings && result.employees) {
                        console.log(result.bookings);
                        const bookingsTableBody = document.getElementById('bookingsTableBody');
                        bookingsTableBody.innerHTML = ''; // Clear previous results

                        result.bookings.forEach(booking => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${booking.customer.name}</td>
                                <td>${booking.service.serviceName}</td>
                                <td>${new Date(booking.startTime).toLocaleString()}</td>
                                <td>${booking.status}</td>
                                <td>
                                    <select class="form-select assign-employee" data-booking-id="${booking.id}">
                                        <option value="">Not Assigned</option>
                                        ${result.employees.map(employee => `
                                            <option value="${employee.id}" ${booking.employee && booking.employee.id === employee.id ? 'selected' : ''}>${employee.name}</option>
                                        `).join('')}
                                    </select>
                                </td>
                            `;
                            bookingsTableBody.appendChild(row);
                        });

                        document.querySelectorAll('.assign-employee').forEach(select => {
                            select.addEventListener('change', function() {
                                const bookingId = this.dataset.bookingId;
                                const employeeId = this.value;
                                assignEmployee(bookingId, employeeId);
                            });
                        });
                    } else {
                        document.getElementById('errorMessage').classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error fetching bookings:', error);
                    document.getElementById('errorMessage').classList.remove('d-none');
                });
        }

        function assignEmployee(bookingId, employeeId) {
            const data = {
                booking_id: bookingId,
                employee_id: employeeId,
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route('admin.assignEmployee') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Employee assigned successfully.');
                } else {
                    alert('Failed to assign employee.');
                }
            })
            .catch(error => {
                console.error('Error assigning employee:', error);
                alert('An error occurred while assigning employee.');
            });
        }
    </script>
@endsection
