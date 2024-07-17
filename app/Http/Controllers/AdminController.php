<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // Display the admin dashboard
    public function index()
    {
        return view('admin.dashboard');
    }

    // Show the form for creating a new user
    public function createUserForm()
    {
        return view('admin.createUser');
    }

    // Store a newly created user in storage
    public function createUser(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|min:3|max:40',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:customer,employee,admin',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create related record based on role
        switch ($request->role) {
            case 'customer':
                Customer::create(['user_id' => $user->id]);
                break;
            case 'employee':
                Employee::create(['user_id' => $user->id]);
                break;
            case 'admin':
                Administrator::create(['user_id' => $user->id]);
                break;
        }

        // Redirect back to the admin dashboard with a success message
        return redirect()->route('admin.dashboard')->with('success', 'User created successfully.');
    }

    public function getBookings(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $validated['start_date'] . ' 00:00:00';
            $endDate = $validated['end_date'] . ' 23:59:59';

            $bookings = Booking::where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('startTime', [$startDate, $endDate])
                    ->orWhereRaw('DATE_ADD(startTime, INTERVAL duration HOUR) BETWEEN ? AND ?', [$startDate, $endDate]);
            })
                ->with(['customer', 'services', 'employee', 'vehicle'])
                ->get();

            // Fetch employees
            $employees = User::where('role', 'employee')->get();

            return response()->json(['bookings' => $bookings, 'employees' => $employees]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching bookings.'], 500);
        }
    }

    public function assignEmployee(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'employee_id' => 'nullable|exists:users,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $booking->employee_id = $request->employee_id;
        $booking->save();

        return response()->json(['success' => true]);
    }
}
