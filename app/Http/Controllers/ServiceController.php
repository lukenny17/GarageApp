<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function showAddServiceForm()
    {
        return view('admin.addService');
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'serviceName' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'duration' => 'required|numeric',
        ]);

        Service::create([
            'serviceName' => $request->serviceName,
            'description' => $request->description,
            'cost' => $request->cost,
            'duration' => $request->duration,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Service added successfully.');
    }
}
