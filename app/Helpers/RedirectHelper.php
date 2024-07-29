<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class RedirectHelper
{
    /**
     * Redirect the user to their respective dashboard based on their role.
     */
    public static function redirectToDashboard(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        } elseif ($user->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        return redirect()->route('login');
    }
}
