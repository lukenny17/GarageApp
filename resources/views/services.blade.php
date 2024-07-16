@extends('shared.layout')

@section('content')
    <section id="services" class="py-3">
        <div class="container">
            <div class="row">
                <h1 class="text-center mb-4">Our Services</h1>
                @foreach ($services as $index => $service)
                    @if ($index % 2 == 0)
                        <div class="row mb-5 align-items-center text-center">
                            <div class="col-md-6">
                                <h3>{{ $service->serviceName }}</h3>
                                <p>{{ $service->description }}</p>
                                <p><strong>Cost:</strong> £{{ number_format($service->cost, 2) }}</p>
                                <p><strong>Duration:</strong> {{ $service->duration }} hours</p>
                            </div>
                            <div class="col-md-6">
                                <img src="/images/{{ strtolower(str_replace(' ', '_', $service->serviceName)) }}.jpg" class="img-fluid rounded-3" alt="{{ $service->serviceName }}" onerror="this.onerror=null;this.src='/images/placeholder.jpg';">
                            </div>
                        </div>
                    @else
                        <div class="row mb-5 align-items-center text-center">
                            <div class="col-md-6 order-md-2">
                                <h3>{{ $service->serviceName }}</h3>
                                <p>{{ $service->description }}</p>
                                <p><strong>Cost:</strong> £{{ number_format($service->cost, 2) }}</p>
                                <p><strong>Duration:</strong> {{ $service->duration }} hours</p>
                            </div>
                            <div class="col-md-6 order-md-1">
                                <img src="/images/{{ strtolower(str_replace(' ', '_', $service->serviceName)) }}.jpg" class="img-fluid rounded-3" alt="{{ $service->serviceName }}" onerror="this.onerror=null;this.src='/images/placeholder.jpg';">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection
