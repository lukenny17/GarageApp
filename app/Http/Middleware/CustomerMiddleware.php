<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->auth->guard($guards)->guest()) {
            return redirect()->guest(route('login'))
            ->with('status', 'Please log in to access this page.');
        }

        $user = $this->auth->guard($guards)->user();

        // Check if the authenticated user has the 'customer' role
        if ($user->role !== 'customer') {
            return redirect()->guest(route('login'))
            ->with('status', 'In order to make a booking, please log in with a customer account.');
        }
        
        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
