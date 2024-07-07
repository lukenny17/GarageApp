<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        // Fetch all services from the database
        $services = Service::all();

        // Fetch all customer's vehicles from the database
        $vehicles = Auth::user()->vehicles;

        // Pass the services to the view
        return view('bookings.create', compact('services', 'vehicles'));
    }

    // Handle the booking submission
    public function store(Request $request)
    {
        // Custom validation rules
        $rules = [
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ];

        // If the vehicle_id is 'new', validate new vehicle fields
        if ($request->vehicle_id === 'new') {
            $rules['make'] = 'required';
            $rules['model'] = 'required';
            $rules['year'] = 'required|integer|min:1900|max:' . date('Y');
            $rules['licensePlate'] = 'required';
        } else {
            // Otherwise, validate vehicle_id
            $rules['vehicle_id'] = 'required|exists:vehicles,id';
        }

        // Validate the request data
        $request->validate($rules);

        // If a new vehicle is being added, create it first
        if ($request->vehicle_id === 'new') {
            $vehicle = Vehicle::create([
                'user_id' => Auth::id(),
                'make' => $request->make,
                'model' => $request->model,
                'year' => $request->year,
                'licensePlate' => $request->licensePlate,
            ]);
            $vehicleId = $vehicle->id;
        } else {
            $vehicleId = $request->vehicle_id;
        }

        // Fetch the service to get the duration
        $service = Service::findOrFail($request->service_id);

        // Create a new booking
        Booking::create([
            'customer_id' => Auth::id(),
            'service_id' => $request->service_id,
            'vehicle_id' => $vehicleId,
            'startTime' => $request->date . ' ' . $request->time,
            'duration' => $service->duration,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.confirmation');
    }

    // Show the booking confirmation
    public function confirmation()
    {
        return view('bookings.confirmation');
    }
}
