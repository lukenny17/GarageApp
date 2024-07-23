<?php

namespace App\Http\Controllers;

use App\Mail\ServiceUpdateApproval;
use App\Models\Service;
use App\Models\Booking;
use App\Models\PendingBookingService;
use App\Models\Review;
use App\Models\Vehicle;
use App\Traits\GeneratesTimeSlots;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    use GeneratesTimeSlots;

    public function showServices()
    {
        $services = Service::all();
        return view('services', compact('services'));
    }

    public function create()
    {
        $services = Service::all();
        $vehicles = Auth::user()->vehicles;
        $timeSlots = $this->getAvailableTimeSlots();

        return view('bookings.create', compact('services', 'vehicles', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $rules = [
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
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

        $serviceIds = $request->service_ids;
        $totalCost = 0;
        $totalDuration = 0;

        foreach ($serviceIds as $serviceId) {
            $service = Service::findOrFail($serviceId);
            $totalCost += $service->cost;
            $totalDuration += $service->duration;
        }

        $booking = Booking::create([
            'customer_id' => Auth::id(),
            'vehicle_id' => $vehicleId,
            'startTime' => $request->date . ' ' . $request->time,
            'duration' => $totalDuration,
            'status' => 'scheduled',
            'cost' => $totalCost,
        ]);

        // Attach the selected services to the booking
        $booking->services()->attach($serviceIds);

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

        // Update the booking to mark the review as submitted
        $booking->review_submitted = true;
        $booking->save();

        return response()->json(['success' => true]);
    }

    public function approveServiceUpdate($id)
    {
        $booking = Booking::findOrFail($id);

        // Detach all existing services
        $booking->services()->detach();

        // Move pending services to actual services
        foreach ($booking->pendingServices as $pendingService) {
            $booking->services()->attach($pendingService->service_id);
        }

        // Clear pending services
        $booking->pendingServices()->delete();

        return redirect()->route('customer.dashboard')->with('success', 'Service update approved successfully.');
    }

    public function rejectServiceUpdate($id)
    {
        $booking = Booking::findOrFail($id);

        // Clear pending services without moving them to actual services
        $booking->pendingServices()->delete();

        return redirect()->route('customer.dashboard')->with('success', 'Service update rejected successfully.');
    }
}
