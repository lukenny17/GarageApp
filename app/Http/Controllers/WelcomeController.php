<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;

class WelcomeController extends Controller
{
    public function index()
    {
        // Retrieve all services for service carousel
        $services = Service::all();

        $fiveStarReviews = Review::where('rating', 5)
            ->with('booking.customer') // Load related booking and customer
            ->get();

        return view('welcome', compact('fiveStarReviews', 'services'));
    }

    public function about()
    {
        return view('about');
    }
}
