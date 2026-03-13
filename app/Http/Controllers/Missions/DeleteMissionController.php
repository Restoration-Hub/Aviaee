<?php

namespace App\Http\Controllers\Missions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mission;

class DeleteMissionController extends Controller
{

    /**
     * Delete mission request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {

        $validated = $request->validate([
            'missionId' => 'required|integer|exists:missions,id',
        ]);

        $mission = Mission::find($validated['missionId']);

        if (!$mission) {
            return response()->json([
                'message' => 'Mission not found'
            ], 404);
        }
        
        // TODO: add authorization check to ensure user has permission to delete this mission??

        $mission->delete();

        return response()->json([
            'message' => 'Mission deleted successfully!'
        ], 200);
    }
}
