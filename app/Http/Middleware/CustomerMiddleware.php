<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest() || (Auth::check() && Auth::user()->role !== 'customer')) {
            return redirect()->guest(route('login'))->with('message', 'In order to make a booking, please log in with a customer account');
        }

        // Works but, ideally, 'Book a Service' page would provide logout option for someone logged in with a non-customer account rather that simply the login page again.

        return $next($request);
    }
}
