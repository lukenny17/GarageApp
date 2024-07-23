<?php

namespace App\Http\Controllers;

use App\Mail\ServiceUpdateApproval;
use App\Models\Booking;
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

    public function updateServices(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Validate the request
        $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        // Update the services
        $serviceIds = $request->service_ids;
        $booking->services()->sync($serviceIds);

        // Send email to customer for approval
        Mail::to($booking->customer->email)->send(new ServiceUpdateApproval($booking));

        return response()->json(['success' => true, 'message' => 'Service update email sent to customer for approval.']);
    }

}
