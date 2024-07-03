<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                'password' => 'required|confirmed|min:6'
                // 'access_code'
            ]
        );

        // create user
        $user = User::create(
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]
        );

        // login (depends if logged in immediately after registration). Add later if needed

        // redirect, potentially change this to dashboard once created
        return redirect()->route('welcome');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate()
    {
        // validate
        $validated = request()->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]
        );

        if(auth()->attempt($validated)){

            // Clear the session if anyone was logged in previoulsy
            request()->session()->regenerate();

            // Potentially change this to dashboard once created
            return redirect()->route('welcome');
        }

        // redirect to login if unsuccesful
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
