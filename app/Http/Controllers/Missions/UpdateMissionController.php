<?php

namespace App\Http\Controllers\Missions;

use App\Enums\MissionStatus;
use Illuminate\Validation\Rules\Enum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mission;

class UpdateMissionController extends Controller
{

    /**
     * Update mission request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {

        $validated = $request->validate([
            'missionId' => 'required|integer|exists:missions,id',
            'status' => 'required|string|in:ordered,packed,inTransit,delivered'
        ]);

        $mission = Mission::find($validated['missionId']);

        if (!$mission) {
            return response()->json([
                'message' => 'Mission not found'
            ], 404);
        }
        
        // TODO: add authorization check to ensure user has permission to update this mission??

        $status = MissionStatus::tryFrom($validated['status']);

        if (!$status) {
            return response()->json([
                'message' => 'Invalid status'
            ], 422);
        }

        $mission->status = $status;
        $mission->save();

        return response()->json([
            'message' => 'Mission updated successfully!'
        ], 200);
    }
}
