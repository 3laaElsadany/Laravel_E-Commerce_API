<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'area' => 'required|string|max:255',
        ]);

        $location = Location::create([...$validated, 'user_id' => Auth::id()]);

        return response()->json($location, 201);
    }

    public function update(Request $request, Location $location)
    {

        $validated = $request->validate([
            'street' => 'sometimes|string|max:255',
            'building' => 'sometimes|string|max:255',
            'area' => 'sometimes|string|max:255',
        ]);

        $location->update($validated);

        return response()->json($location);
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json(['message' => 'Location deleted successfully']);
    }
}
