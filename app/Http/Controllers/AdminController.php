<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
}
