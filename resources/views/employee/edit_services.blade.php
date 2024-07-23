@extends('shared.layout')

@section('content')
    <section id="editServices" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Edit Services</h2>
                    <form action="{{ route('employee.update.services', $booking->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="service_ids" class="form-label">Services</label>
                            @foreach ($services as $service)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="service_{{ $service->id }}"
                                        name="service_ids[]" value="{{ $service->id }}"
                                        @if (in_array($service->id, $booking->services->pluck('id')->toArray())) checked @endif>
                                    <label class="form-check-label" for="service_{{ $service->id }}">
                                        {{ $service->serviceName }}: £{{ $service->cost }}
                                    </label>
                                </div>
                            @endforeach
                            @error('service_ids')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn custom-btn">Update Services</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
