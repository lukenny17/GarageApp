<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function showServices()
    {
        $services = Service::all();
        return view('services', compact('services'));
    }

    public function create()
    {
        $services = Service::all();
        $vehicles = Auth::user()->vehicles;
        return view('bookings.create', compact('services', 'vehicles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'status' => 'in:scheduled,completed,cancelled'
        ];

        if ($request->vehicle_id === 'new') {
            $rules['make'] = 'required';
            $rules['model'] = 'required';
            $rules['year'] = 'required|integer|min:1900|max:' . date('Y');
            $rules['licensePlate'] = 'required';
        } else {
            $rules['vehicle_id'] = 'required|exists:vehicles,id';
        }

        $request->validate($rules);

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

        $service = Service::findOrFail($request->service_id);

        Booking::create([
            'customer_id' => Auth::id(),
            'service_id' => $request->service_id,
            'vehicle_id' => $vehicleId,
            'startTime' => $request->date . ' ' . $request->time,
            'duration' => $service->duration,
            'status' => 'scheduled',
        ]);

        return redirect()->route('bookings.confirmation');
    }

    public function confirmation()
    {
        return view('bookings.confirmation');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
        return response()->json(['success' => true]);
    }

    public function rescheduleBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'startTime' => $request->date . ' ' . $request->time,
            'status' => 'scheduled',
        ]);
        return response()->json(['success' => true]);
    }

    public function leaveReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $booking = Booking::findOrFail($id);

        $review = Review::create([
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['success' => true]);
    }
}
