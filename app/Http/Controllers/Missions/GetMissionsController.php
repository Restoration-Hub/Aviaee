<?php

namespace App\Http\Controllers\Missions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Interfaces\IMissionRepository;

class GetMissionsController extends Controller
{
    /**
     * Get missions request
     *
     * @param Request $request
     * @param IMissionRepository $missions
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, IMissionRepository $missions)
    {
        $email = $request->user()?->email;

        if (! $email) {
            return response()->json(['missions' => []], 200);
        }

        $entities = $missions->getMissions($email);

        $payload = array_map(function ($m) {
            return [
                'id' => $m->id,
                'missionName' => $m->missionName,
                'status' => $m->status,
                'startingLocation' => $m->startingLocation,
                'destination' => $m->destination,
                'email' => $m->email,
                'dateCreated' => (string) $m->dateCreated,
                // note: $m->dateDelivered may be null; return null instead of string to avoid
                // defaulting to current timestamp when casting.
                'dateDelivered' => $m->dateDelivered ? (string) $m->dateDelivered : null,
            ];
        }, $entities);

        return response()->json(['missions' => $payload], 200);
    }
}
