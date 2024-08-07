@extends('shared.layout')

@section('content')
    <section id="about-us">
        <div class="hero-about">
            <div class="container">
                <div class="row">
                    {{-- Empty left half --}}
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <h1 class="text-center mb-4">About Us</h1>
                        <p>
                            Welcome to {{ config('app.name') }}, your trusted automotive service provider since 1992. Our
                            certified technicians are dedicated to keeping your vehicle in peak condition. We offer a wide
                            range of services, including inspections, oil changes, tyre changes, brake
                            services, battery replacements, AC checks, transmission fluid flushes, and engine diagnostics.
                        </p>
                        <p>
                            Our mission is to provide honest and reliable automotive services that exceed customer
                            expectations and get you back on the open road.
                        </p>
                        <p>
                            We look forward to serving you. If you have any questions or need to schedule an appointment,
                            please contact us.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
