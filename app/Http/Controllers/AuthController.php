<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function store()
    {
        // validate
        $validated = request()->validate(
            [
                'name' => 'required|min:3|max:40',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
            ]
        );

        // create user
        $user = User::create(
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer', // Default role for registration is customer
            ]
        );
        
        // Create a corresponding customer record
        Customer::create(['user_id' => $user->id]);

        // Send email verification notification
        event(new Registered($user));

        // Redirect to the verification notice page
        return redirect()->route('verification.notice');
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

            // Redirect to the intended URL if any, or to the welcome page - this is practical for users who wanted to access the bookings page (but werent logged in), then
            // were redirected to login, and then would automatically be returned to the bookings page (rather than the default welcome page)
            return redirect()->intended(route('welcome'));
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
