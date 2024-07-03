<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use Illuminate\Http\Request;

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
            // service_id must be present and must exist in the id column of the services table.
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            // Add other validation rules as needed
        ]);

        // Create a new booking (takes an array of k-v pairs where keys are column names in bookings table and values are data to be inserted)
        Booking::create([
            'user_id' => auth()->id(), // Assumes the user is authenticated - NOT YET
            'service_id' => $request->service_id,
            'start_time' => $request->date . ' ' . $request->time,
            'status' => 'pending',
        ]);

        // Takes user to route in web.php labelled 'bookings.confirmation', which then redirects back to confirmation() function below
        return redirect()->route('bookings.confirmation');
    }

    // Show the booking confirmation
    public function confirmation()
    {
        return view('bookings.confirmation');
    }

    // Additional methods for managing bookings
}