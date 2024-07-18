<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function store()
    {
        // Validate the request
        $validated = request()->validate([
            'name' => 'required|min:3|max:40',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'nullable|digits_between:10,15',
        ]);
    
        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'customer', // Default role for registration is customer
        ]);
    
        // Create a corresponding customer record
        Customer::create(['user_id' => $user->id]);
    
        // Send email verification notification
        event(new Registered($user));
    
        // Redirect to the registration success page
        return redirect()->route('registration.success');
    }
    

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate()
    {
        // Validate the request data
        $validated = request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (auth()->attempt($validated)) {

            // Clear the session if anyone was logged in previously
            request()->session()->regenerate();

            // Redirect users to intended route (i.e., bookings if that was clicked on initially) or, by default, welcome page
            return redirect()->route('customer.dashboard');
        }

        // Redirect to login if unsuccessful
        return redirect()->route('login')->withErrors([
            'email' => 'No matching user found with the provided email and password'
        ]);
    }

    public function logout()
    {
        // Inbuilt function for Laravel to logout user
        auth()->logout();

        // Invalidate any existing sessions that exist, then call regenerateToken, then redirect to welcome page
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
