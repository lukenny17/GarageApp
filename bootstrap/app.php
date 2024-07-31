<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // Scheduled the reminders:send command to run at 9am each day
        // Run php artisan queue:work and php artisan schedule:run (in separate terminals, in that order) to fetch and send email reminders with higher frequency to test.
        // Emails will only be queued when the scheduled command runs (at 9am). Command queries database for bookings and queues emails to be sent.
        // If schedule:run is manually run, checks if the right time to run the command - if not, command wont be executed and no emails will be queued
        $schedule->command('reminders:send')->dailyAt('09:00');
    })
    ->create();

