@extends('shared/email')

@section('content')
    <div class="email-container">
        <h1 class="email-header">Upcoming Booking Reminder</h1>
        <p class="email-body">Dear {{ $booking->customer->name }},</p>
        <p class="email-body">This is a friendly reminder that you have a booking scheduled for tomorrow. Here are the
            details:</p>

        <div class="email-content">
            <h3 class="email-subheader">Booking Details:</h3>
            <p class="email-body"><strong>Date/Time:</strong>
                {{ \Carbon\Carbon::parse($booking->startTime)->format('Y-m-d @ H:i') }}</p>

            <h3 class="email-subheader">Services:</h3>
            <ul>
                @foreach ($booking->services as $service)
                    <li class="email-body">{{ $service->serviceName }}</li>
                @endforeach
            </ul>
        </div>

        <p class="email-body">We look forward to serving you. If you have any questions or need to make changes to your
            booking, please visit your customer dashboard or contact us at your earliest convenience.</p>

        <p class="email-body">Best regards,</p>
        <p class="email-body"><strong>{{ config('app.name') }}</strong></p>
    </div>
@endsection
