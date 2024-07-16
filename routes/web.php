<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/about', [WelcomeController::class, 'about'])->name('about');

Route::get('/services', [BookingController::class, 'showServices'])->name('services');

// Customer Dashboard
Route::middleware(['auth', 'customer', 'verified'])->group(function () {
    Route::get('/customer', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/customer/bookings/cancel/{id}', [CustomerController::class, 'cancelBooking']);
    Route::post('/customer/bookings/reschedule/{id}', [CustomerController::class, 'rescheduleBooking']);
    Route::post('/customer/bookings/review/{id}', [CustomerController::class, 'leaveReview'])->name('bookings.review');
    Route::get('/customer/vehicles/{id}', [CustomerController::class, 'getVehicle']);
    Route::post('/customer/vehicles/update/{id}', [CustomerController::class, 'updateVehicle']);
    Route::get('/customer/settings', [CustomerController::class, 'settings'])->name('customer.settings');
    Route::post('/customer/update-email', [CustomerController::class, 'updateEmail'])->name('customer.updateEmail');
    Route::post('/customer/update-password', [CustomerController::class, 'updatePassword'])->name('customer.updatePassword');
    Route::post('/customer/delete-account', [CustomerController::class, 'destroyAccount'])->name('customer.destroyAccount');
});

// Route for creating a booking, model_name.action_name
Route::middleware(['customer', 'verified'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
});


// Route for opening registration page
Route::get('/register', [AuthController::class, 'register'])->name('register');

// Route for submitting registration/user details details, don't need a unique name because it will be identical to view page but method name should be store
Route::post('/register', [AuthController::class, 'store']);

// Route for opening login page
Route::get('/login', [AuthController::class, 'login'])->name('login');

// Route for authenticating login details
Route::post('/login', [AuthController::class, 'authenticate']);

// Route for logging out
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Displays the email verification notice to users who have registered but not yet verified their email addresses
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Handles the actual verification of the email address when the user clicks the verification link sent to their email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/login');
    })->middleware('signed')->name('verification.verify');

    // Allows users to request a new verification email if they haven't received the original one
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Route for accessing admin dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/create-user', [AdminController::class, 'createUserForm'])->name('admin.createUserForm');
    Route::post('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/get-bookings', [AdminController::class, 'getBookings'])->name('admin.getBookings');
    Route::post('/admin/assign-employee', [AdminController::class, 'assignEmployee'])->name('admin.assignEmployee');
});