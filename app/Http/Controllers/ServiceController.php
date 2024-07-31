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

    public function editServiceForm()
    {
        $services = Service::all();
        return view('admin.editService', compact('services'));
    }

    public function getService($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'serviceName' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'duration' => 'required|numeric',
        ]);

        $service = Service::findOrFail($id);
        $service->serviceName = $request->serviceName;
        $service->description = $request->description;
        $service->cost = $request->cost;
        $service->duration = $request->duration;
        $service->save();

        return redirect()->route('admin.dashboard')->with('success', 'Service updated successfully');
    }

    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['success' => 'Service deleted successfully']);
    }
}
