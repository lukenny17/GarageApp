<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function getVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json(['success' => true, 'vehicle' => $vehicle]);
    }

    public function updateVehicle(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->only(['make', 'model', 'year', 'licensePlate']));

        return response()->json(['success' => true]);
    }
}
