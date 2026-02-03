<?php

namespace App\Http\Controllers\Missions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mission;

class CreateMissionController extends Controller
{

    /**
     * Create mission request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {

        $validated = $request->validate([
            'missionName' => 'required|string|max:255',
            'startingLocation' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        $mission = Mission::create([
            'mission_name' => $validated['missionName'],
            'starting_location' => $validated['startingLocation'],
            'destination' => $validated['destination'],
        ]);

        return response()->json([
            'message' => 'Mission created successfully',
            'mission' => $mission
        ], 200);
    }
}
