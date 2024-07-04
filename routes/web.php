<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Look into route grouping to refactor at the end

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Route for creating a booking, model_name.action_name
Route::get('/bookings', [BookingController::class, 'create'])->name('bookings.create');

// Use correct controller for process, send it to 'store' method (see BookingController.php) to store a booking
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

Route::get('/bookings/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');

// Route for opening registration page
Route::get('/register', [AuthController::class, 'register'])->name('register');

// Route for submitting registration/user details details, dont need a unique name because it will be identical to view page but method name should be store
Route::post('/register', [AuthController::class, 'store']);

// Route for opening login page
Route::get('/login', [AuthController::class, 'login'])->name('login');

// Route for authenticating login details
Route::post('/login', [AuthController::class, 'authenticate']);

// Route for authenticating login details
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'admin']);
Route::get('/admin/create-user', [AdminController::class, 'createUserForm'])->name('admin.createUserForm')->middleware(['auth', 'admin']);
Route::post('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.createUser')->middleware(['auth', 'admin']);

