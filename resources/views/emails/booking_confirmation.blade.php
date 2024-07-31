@extends('shared/email')

@section('content')
    <div class="email-container">
        <h1 class="email-header">Booking Confirmation</h1>
        <p class="email-body">Dear {{ $booking->customer->name }},</p>
        <p class="email-body">Thank you for your booking. Here are the details of your booking:</p>

        <div class="email-content">
            <h3 class="email-subheader">Booking Details:</h3>
            <p class="email-body"><strong>Date/Time:</strong> {{ \Carbon\Carbon::parse($booking->startTime)->format('Y-m-d @ H:i') }}</p>
            <p class="email-body"><strong>Duration:</strong> {{ $booking->duration }} hours</p>
            <p class="email-body"><strong>Cost:</strong> £{{ $booking->cost }}</p>
            <p class="email-body"><strong>Vehicle Details:</strong> {{ $booking->vehicle->make }}, {{ $booking->vehicle->model }}, {{ $booking->vehicle->licensePlate }}</p>

            <h3 class="email-subheader">Services:</h3>
            <ul class="email-body">
                @foreach ($booking->services as $service)
                    <li>{{ $service->serviceName }}</li>
                @endforeach
            </ul>
        </div>

        <p class="email-body">We look forward to serving you. If you have any questions or need to make changes to your booking, please visit your customer dashboard or contact us at your earliest convenience.</p>

        <p class="email-body">Best regards,</p>
        <p class="email-body"><strong>{{ config('app.name') }}</strong></p>
    </div>
@endsection
