<?php

namespace App\Http\Controllers;

use App\Mail\ServiceUpdateApproval;
use App\Models\Booking;
use App\Models\PendingBookingService;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $employeeId = Auth::id();
        $bookings = Booking::where('employee_id', $employeeId)->orderBy('startTime', 'asc')->get();
        return view('employee.dashboard', compact('bookings'));
    }

    public function toggleBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = $request->input('status');
        $booking->save();

        return response()->json(['success' => true]);
    }

    public function editServices($id)
    {
        $booking = Booking::findOrFail($id);
        $services = Service::all();
        $selectedServices = $booking->services->pluck('id')->toArray();

        return view('employee.partials.services_checkboxes', compact('services', 'selectedServices', 'booking'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = $request->input('status');
        $booking->save();

        return response()->json(['success' => true]);
    }

    public function updateServices(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Validate the request
        $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        // Store pending service updates
        PendingBookingService::where('booking_id', $booking->id)->delete(); // Clear existing pending changes

        foreach ($request->service_ids as $serviceId) {
            PendingBookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $serviceId,
            ]);
        }

        // Refresh the booking model to include the latest pending services
        $booking->load('pendingServices');

        // Send email to customer for approval
        Mail::to($booking->customer->email)->send(new ServiceUpdateApproval($booking));

        return response()->json(['success' => true, 'message' => 'Service update email sent to customer for approval.']);
    }
}
