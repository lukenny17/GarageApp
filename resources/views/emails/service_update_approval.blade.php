<!DOCTYPE html>
<html>

<head>
    <title>Service Update Approval</title>
</head>

<body>
    <h1>Service Update Approval</h1>
    <p>Dear {{ $booking->customer->name }},</p>
    <p>An update to your booking services has been proposed. Please review and approve or reject the changes.</p>

    <h3>Booking Details:</h3>
    <p><strong>Date/Time:</strong> {{ \Carbon\Carbon::parse($booking->startTime)->format('Y-m-d @ H:i') }}</p>

    <h3>Existing Services:</h3>
    <ul>
        @foreach ($booking->services as $service)
            <li>{{ $service->serviceName }}</li>
        @endforeach
    </ul>

    <h3>Proposed Services:</h3>
    <ul>
        @foreach ($booking->pendingServices as $pendingService)
            <li>{{ $pendingService->service->serviceName }}</li>
        @endforeach
    </ul>

    <p>
        <a href="{{ route('bookings.approveServiceUpdate', $booking->id) }}">Approve</a> |
        <a href="{{ route('bookings.rejectServiceUpdate', $booking->id) }}">Reject</a>
    </p>
</body>

</html>
