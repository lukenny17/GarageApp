<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        // Fetch all services from the database
        $services = Service::all();

        // Pass the services to the view
        return view('bookings.create', compact('services'));
    }

    // Handle the booking submission
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        // Create a new booking
        Booking::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'start_time' => $request->date . ' ' . $request->time,
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
