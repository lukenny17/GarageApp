<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $bookings = Booking::where('customer_id', $user->id)->get();
        $vehicles = Vehicle::where('user_id', $user->id)->get();

        return view('customer.dashboard', compact('bookings', 'vehicles'));
    }

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
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
    
        Review::create([
            'booking_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
    
        $booking = Booking::find($id);
        $booking->review_submitted = 1;
        $booking->save();
    
        return response()->json(['success' => true]);
    }
    


    public function getVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json(['success' => true, 'vehicle' => $vehicle]);
    }

    public function updateVehicle(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->only(['make', 'model', 'year', 'licensePlate']));

        return response()->json(['success' => true]);
    }
}
