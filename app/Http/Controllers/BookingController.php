<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{

    public function showServices()
    {
        // Fetch all services from the database
        $services = Service::all();

        // Pass the services to the view
        return view('services', compact('services'));
    }

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

    // Methods for customer dashboard, allowing customers to cancel/reschedule bookings and leave reviews
    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['success' => true]);
    }

    public function rescheduleBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'startTime' => $request->date . ' ' . $request->time,
        ]);

        return response()->json(['success' => true]);
    }

    public function leaveReview(Request $request, $id)
    {
        Log::info('Leave review request received', ['request' => $request->all(), 'booking_id' => $id]);
    
        // Validate the request data
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);
    
        // Find the booking
        $booking = Booking::findOrFail($id);
    
        // Create a new review associated with the booking
        $review = Review::create([
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
    
        Log::info('Review created successfully', ['review' => $review]);
    
        return response()->json(['success' => true]);
    }
    
}
