<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class , 'index']);

Route::get('/services', function () {
    return view('services');
});
