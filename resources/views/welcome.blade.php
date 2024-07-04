@extends('shared.layout')

@section('content')
{{-- Hero section --}}
<div class="hero">
    <div class="container">
        <h1 class="display-4">{{config('app.name')}}</h1>
        <p class="lead">Rev Up Your Service.</p>
        <a href="/bookings" class="btn btn-outline-light btn-lg">Book Now</a>
    </div>
</div>

{{-- Services section --}}
<section id="services" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Services</h2>
        <div class="row" style="height: 100%;">
            <div class="col-md-6">
                <div id="servicesCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="/images/image1.jpg" class="d-block w-100 rounded-3" alt="Service 1">
                            <div class="carousel-caption">
                                <h5>Full MOT</h5>
                                {{-- <p>Short description of Service 1.</p> --}}
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="/images/image2.jpg" class="d-block w-100 rounded-3" alt="Service 2">
                            <div class="carousel-caption">
                                <h5>Engine Diagnostics</h5>
                                {{-- <p>Short description of Service 2.</p> --}}
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="/images/image10.jpg" class="d-block w-100 rounded-3" alt="Service 3">
                            <div class="carousel-caption">
                                <h5>Oil Change</h5>
                                {{-- <p>Short description of Service 3.</p> --}}
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-center">
                {{-- <h4 class="mb-3">First-Class Auto Services</h4> --}}
                <p>We offer a wide range of services to meet your needs. From routine maintenance to major repairs,
                    our experienced team is here to help.</p>
                <a href="/services" class="btn btn-dark">Learn More</a>
            </div>
        </div>
    </div>
</section>

{{-- Horizontal Line --}}
<hr class="border-gray-300">

{{-- Testimonials carousel --}}
<section id="testimonials" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Testimonials</h2>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center">
                        <div class="col-md-8">
                            <div class="testimonial text-center">
                                <p class="lead">"Excellent service! My car has never run better. Highly recommend
                                    Luxe Auto."</p>
                                <p>- John Doe</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center">
                        <div class="col-md-8">
                            <div class="testimonial text-center">
                                <p class="lead">"Professional and friendly staff. They fixed my car quickly and
                                    at
                                    a fair price."</p>
                                <p>- Jane Smith</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

{{-- Horizontal Line --}}
<hr class="border-gray-300 mb-0">

{{-- Map section --}}
<div class="map-container">
    "<iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9980.218535005075!2d1.06351354024961!3d51.29171865597924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47deca36929a4527%3A0xe17bfa0b0060a838!2sUniversity%20of%20Kent!5e0!3m2!1sen!2suk!4v1719660809790!5m2!1sen!2suk"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
@endsection