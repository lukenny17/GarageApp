<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function settings()
    {
        return view('customer.settings');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->email = $request->email;
        $user->email_verified_at = null; // Require re-verification
        $user->save();

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        return redirect()->route('customer.settings')->with('status', 'Email updated! Please verify your new email.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('customer.settings')->with('status', 'Password updated successfully.');
    }

    public function destroyAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        Auth::logout();
        $user->delete();

        return redirect('/')->with('status', 'Account deleted successfully.');
    }
}
