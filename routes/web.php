<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/services', [BookingController::class, 'showServices'])->name('services');

// Customer Dashboard
Route::middleware(['auth', 'customer', 'verified'])->group(function () {
    Route::get('/customer', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/customer/bookings/cancel/{id}', [CustomerController::class, 'cancelBooking'])->name('customer.bookings.cancel');
    Route::post('/customer/bookings/reschedule/{id}', [CustomerController::class, 'rescheduleBooking'])->name('customer.bookings.reschedule');
    Route::post('/customer/bookings/review/{id}', [CustomerController::class, 'leaveReview'])->name('bookings.review');
    Route::get('/customer/vehicles/{id}', [CustomerController::class, 'getVehicle']);
    Route::post('/customer/vehicles/update/{id}', [CustomerController::class, 'updateVehicle']);
    Route::get('/customer/settings', [CustomerController::class, 'settings'])->name('customer.settings');
    Route::post('/customer/update-email', [CustomerController::class, 'updateEmail'])->name('customer.updateEmail');
    Route::post('/customer/update-phone', [CustomerController::class, 'updatePhone'])->name('customer.updatePhone');
    Route::post('/customer/update-password', [CustomerController::class, 'updatePassword'])->name('customer.updatePassword');
    Route::post('/customer/delete-account', [CustomerController::class, 'destroyAccount'])->name('customer.destroyAccount');
});

// Route for customer to approve updates to services (recommended by employee)
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings/{id}/approve', [BookingController::class, 'approveServiceUpdate'])->name('bookings.approveServiceUpdate');
    Route::get('/bookings/{id}/reject', [BookingController::class, 'rejectServiceUpdate'])->name('bookings.rejectServiceUpdate');
});

// Employee Dashboard
Route::middleware(['auth', 'employee'])->group(function () {
    Route::get('/employee', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::post('/employee/bookings/toggle-status/{id}', [EmployeeController::class, 'toggleBookingStatus']);
    Route::get('/employee/bookings/{id}/services', [EmployeeController::class, 'editServices'])->name('employee.bookings.services');
    Route::post('/employee/bookings/{id}/services', [EmployeeController::class, 'updateServices'])->name('employee.bookings.updateServices');
    Route::post('/employee/bookings/{id}/update-status', [EmployeeController::class, 'updateBookingStatus']);
});

// Route for creating a booking, model_name.action_name
Route::middleware(['customer', 'verified'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
});

// Route for accessing admin dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/create-user', [AdminController::class, 'createUserForm'])->name('admin.createUserForm');
    Route::post('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/get-bookings', [AdminController::class, 'getBookings'])->name('admin.getBookings');
    Route::post('/admin/assign-employee', [AdminController::class, 'assignEmployee'])->name('admin.assignEmployee');
    Route::get('/admin/addService', [ServiceController::class, 'showAddServiceForm'])->name('admin.addServiceForm');
    Route::post('/admin/addService', [ServiceController::class, 'storeService'])->name('admin.storeService');
});

// Route for profile management - added with Laravel Breeze

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
