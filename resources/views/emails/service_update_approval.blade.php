<!DOCTYPE html>
<html>
<head>
    <title>Service Update Approval</title>
</head>
<body>
    <p>Dear {{ $booking->customer->name }},</p>
    <p>Your booking with ID {{ $booking->id }} has been updated with the following services:</p>
    <ul>
        @foreach ($booking->services as $service)
            <li>{{ $service->serviceName }}: £{{ $service->cost }}</li>
        @endforeach
    </ul>
    <p>Please click the link below to approve the changes:</p>
    <p>
        <a href="{{ route('bookings.approveServiceUpdate', $booking->id) }}">Approve Service Update</a>
    </p>
    <p>Thank you!</p>
</body>
</html>