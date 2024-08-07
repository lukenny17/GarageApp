@extends('shared.layout')

@section('content')
    <section id="adminDashboard" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="mb-4 text-center">Admin Dashboard</h2>

                    {{-- Display success message --}}
                    @if (session('success'))
                        <div class="alert alert-success text-center mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            {{-- Booking Filters --}}
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Filter Bookings</h4>
                                </div>
                                <div class="card-body">
                                    <form id="fetchBookingsForm" class="row justify-content-center align-items-center">
                                        <div class="col-auto d-flex align-items-center mb-2">
                                            <label for="startDate" class="me-2">From</label>
                                            <input type="date" id="startDate" name="startDate" class="form-control">
                                        </div>
                                        <div class="col-auto d-flex align-items-center mb-2">
                                            <label for="endDate" class="me-2">To</label>
                                            <input type="date" id="endDate" name="endDate" class="form-control">
                                        </div>
                                        <div class="col-auto d-flex align-items-center mb-2">
                                            <button type="button" id="viewBookingsButton" class="btn custom-btn">View
                                                Bookings</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Create New User Button --}}
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Actions</h4>
                                </div>
                                <div class="card-body text-center">
                                    <a href="{{ route('admin.createUserForm') }}" class="btn custom-btn mb-2">Create
                                        User</a>
                                    <a href="{{ route('admin.addServiceForm') }}" class="btn custom-btn mb-2">Add
                                        Service</a>
                                    <a href="{{ route('admin.editServiceForm') }}" class="btn custom-btn mb-2">Edit / Delete
                                        Service</a>
                                    <a href="{{ route('admin.createBookingForm') }}" class="btn custom-btn mb-2">Create
                                        Booking</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bookings Table --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">Bookings</h4>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Service(s)</th>
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

                    {{-- Calendar Section --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">Calendar</h4>
                        </div>
                        <div class="card-body" style="height: 500px; overflow-y: auto;">
                            <div id="calendar"></div>
                        </div>
                    </div>

                    {{-- Event Details Modal --}}
                    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eventDetailsModalLabel">Booking Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p id="eventTitle"></p>
                                    <p id="eventStart"></p>
                                    <p id="eventEnd"></p>
                                    <p id="eventStatus"></p>
                                    <p id="eventStaff"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn custom-btn" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FullCalendar CSS --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />

    {{-- FullCalendar JS --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');

            // Set default dates: today and one week from today
            const today = new Date().toISOString().split('T')[0];
            const oneWeekFromToday = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

            // Initialise the date inputs with the default values
            startDate.value = today;
            endDate.value = oneWeekFromToday;

            // Add a click event listener to the "View Bookings" button
            document.getElementById('viewBookingsButton').addEventListener('click', fetchBookings);

            // Function to fetch bookings from the server
            function fetchBookings() {
                const startDateValue = startDate.value;
                const endDateValue = endDate.value;

                if (!startDateValue || !endDateValue) {
                    alert('Please select both start and end dates.');
                    return;
                }

                // Prepare the data to send in the request, including CSRF token for security
                const data = {
                    start_date: startDateValue,
                    end_date: endDateValue,
                    _token: '{{ csrf_token() }}'
                };

                // Use Fetch API to send a POST request to fetch bookings
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
                        // Check if bookings and employees data are present
                        if (result.bookings && result.employees) {
                            const bookingsTableBody = document.getElementById('bookingsTableBody');
                            bookingsTableBody.innerHTML = ''; // Clear previous results from the table

                            result.bookings.forEach(booking => {
                                const row = document.createElement('tr');
                                // Join service names with commas for display
                                const services = booking.services.map(service => service.serviceName)
                                    .join(', ');
                                row.innerHTML = `
                                    <td>${booking.customer.name}</td>
                                    <td>${services}</td>
                                    <td>${new Date(booking.startTime).toLocaleString()}</td>
                                    <td>${booking.status}</td>
                                    <td>
                                        <select class="form-select assign-employee" data-booking-id="${booking.id}">
                                            <option value="">Not Assigned</option>
                                            ${result.employees.map(employee =>`
                                            <option value="${employee.id}" ${booking.employee && booking.employee.id === employee.id ? 'selected' : ''}>${employee.name}</option>`).join('')}
                                        </select>
                                    </td>
                                `;
                                bookingsTableBody.appendChild(row); // Append the row to the table body
                            });

                            document.querySelectorAll('.assign-employee').forEach(select => {
                                select.addEventListener('change', function() {
                                    const bookingId = this.dataset.bookingId;
                                    const employeeId = this.value;
                                    assignEmployee(bookingId,
                                        employeeId); // Call function to assign employee
                                });
                            });

                            updateCalendar(result.bookings);
                        } else {
                            document.getElementById('errorMessage').classList.remove('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching bookings:', error);
                        document.getElementById('errorMessage').classList.remove('d-none');
                    });
            }

            // Function to assign an employee to a booking
            function assignEmployee(bookingId, employeeId) {
                const data = {
                    booking_id: bookingId,
                    employee_id: employeeId,
                    _token: '{{ csrf_token() }}'
                };

                // Use Fetch API to send a POST request to assign the employee
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

            // Function to update the calendar with booking events
            function updateCalendar(bookings) {
                const calendarEl = document.getElementById('calendar');
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },

                    // Event click handler to display booking details
                    eventClick: function(info) {
                        info.jsEvent.preventDefault();
                        document.getElementById('eventTitle').textContent = 'Service: ' + info.event
                            .title;
                        document.getElementById('eventStart').textContent = 'Start: ' + (info.event
                            .start ? info.event.start.toLocaleString() : 'No start time');
                        document.getElementById('eventEnd').textContent = 'End: ' + (info.event.end ?
                            info.event.end.toLocaleString() : 'No end time');
                        document.getElementById('eventStatus').textContent = 'Status: ' + info.event
                            .extendedProps.status;
                        document.getElementById('eventStaff').textContent = 'Assigned to: ' + info.event
                            .extendedProps.staffName;
                        new bootstrap.Modal(document.getElementById('eventDetailsModal')).show();
                    },
                    // Convert bookings to calendar events
                    events: bookings.map(booking => ({
                        title: booking.services.map(service => service.serviceName).join(', '),
                        start: booking.startTime,
                        end: new Date(new Date(booking.startTime).getTime() + booking.duration *
                            60 * 60 * 1000),
                        extendedProps: {
                            status: booking.status,
                            staffName: booking.employee ? booking.employee.name : 'Not Assigned'
                        }
                    }))
                });

                calendar.render();
            }

            // Initialise the calendar on page load
            updateCalendar([]);
        });
    </script>
@endsection
