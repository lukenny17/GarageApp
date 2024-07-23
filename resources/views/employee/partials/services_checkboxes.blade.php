@foreach ($services as $service)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="service_{{ $service->id }}"
            name="service_ids[]" value="{{ $service->id }}"
            @if ($booking->services->contains($service->id)) checked @endif>
        <label class="form-check-label" for="service_{{ $service->id }}">
            {{ $service->serviceName }}: £{{ $service->cost }}
        </label>
    </div>
@endforeach
