<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmationMail;
use App\Models\Service;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Traits\GeneratesTimeSlots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $timeSlots = $this->getAvailableTimeSlots();

        if (Auth::user()->role == 'admin') {
            $customers = User::where('role', 'customer')->get();
            $vehicles = Vehicle::all();
            return view('admin.createBooking', compact('services', 'vehicles', 'timeSlots', 'customers'));
        } else {
            $vehicles = Auth::user()->vehicles;
            return view('bookings.create', compact('services', 'vehicles', 'timeSlots'));
        }
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

        if (Auth::user()->role == 'admin') {
            $rules['customer_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        if ($request->vehicle_id === 'new') {
            $vehicle = Vehicle::create([
                'user_id' => Auth::user()->role == 'admin' ? $request->customer_id : Auth::id(),
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
            'customer_id' => Auth::user()->role == 'admin' ? $request->customer_id : Auth::id(),
            'vehicle_id' => $vehicleId,
            'startTime' => $request->date . ' ' . $request->time,
            'duration' => $totalDuration,
            'status' => 'scheduled',
            'cost' => $totalCost,
        ]);

        // Attach the selected services to the booking
        $booking->services()->attach($serviceIds);

        // Send email to booking confirming booking
        Mail::to($booking->customer->email)->send(new BookingConfirmationMail($booking));

        return Auth::user()->role == 'admin' ? redirect()->route('admin.dashboard')->with('success', 'Booking created successfully.') : redirect()->route('bookings.confirmation');
    }

    public function confirmation()
    {
        return view('bookings.confirmation');
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

    public function getCustomerVehicles($customerId)
    {
        $customer = User::findOrFail($customerId);
        $vehicles = $customer->vehicles;

        return response()->json(['vehicles' => $vehicles]);
    }
}
