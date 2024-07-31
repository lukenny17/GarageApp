@extends('shared/email')

@section('content')
    <div class="email-container">
        <h1 class="email-header">Service Update Approval</h1>
        <p class="email-body">Dear {{ $booking->customer->name }},</p>
        <p class="email-body">An update to your booking services has been proposed. Please review and approve or reject the changes.</p>

        <div class="email-content">
            <h3 class="email-subheader">Booking Details:</h3>
            <p class="email-body"><strong>Date/Time:</strong> {{ \Carbon\Carbon::parse($booking->startTime)->format('Y-m-d @ H:i') }}</p>

            <h3 class="email-subheader">Existing Services:</h3>
            <ul class="email-body">
                @foreach ($booking->services as $service)
                    <li>{{ $service->serviceName }}</li>
                @endforeach
            </ul>

            <h3 class="email-subheader">Proposed Services:</h3>
            <ul class="email-body">
                @foreach ($booking->pendingServices as $pendingService)
                    <li>{{ $pendingService->service->serviceName }}</li>
                @endforeach
            </ul>
        </div>

        <p class="email-body">
            <a href="{{ route('bookings.approveServiceUpdate', $booking->id) }}" class="btn btn-success">Approve</a> |
            <a href="{{ route('bookings.rejectServiceUpdate', $booking->id) }}" class="btn btn-danger">Reject</a>
        </p>
    </div>
@endsection
