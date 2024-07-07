<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/about', [WelcomeController::class, 'about'])->name('about');

Route::get('/services', [BookingController::class, 'showServices'])->name('services');

// Customer Dashboard
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/customer', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/customer/bookings/cancel/{id}', [CustomerController::class, 'cancelBooking']);
    Route::post('/customer/bookings/reschedule/{id}', [CustomerController::class, 'rescheduleBooking']);
    Route::post('/customer/bookings/review/{id}', [CustomerController::class, 'leaveReview']);
    Route::get('/customer/vehicles/{id}', [CustomerController::class, 'getVehicle']);
    Route::post('/customer/vehicles/update/{id}', [CustomerController::class, 'updateVehicle']);
});

// Route for creating a booking, model_name.action_name
Route::middleware(['customer'])->group(function () {
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

// Route for accessing admin dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/create-user', [AdminController::class, 'createUserForm'])->name('admin.createUserForm');
    Route::post('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.createUser');
});
