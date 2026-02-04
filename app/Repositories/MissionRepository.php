<?php

namespace App\Repositories;

use App\Domain\Interfaces\IMissionRepository;
use App\Domain\Entities\MissionEntity;
use App\Models\Mission as MissionModel;
use App\Domain\Entities\Date;
use Illuminate\Support\Facades\Auth;

class MissionRepository implements IMissionRepository
{
    /**
     * Return all missions for a given creator email as an array of MissionEntity
     *
     * @param string $email
     * @return MissionEntity[]
     */
    public function getMissions(string $email): array
    {
        $models = MissionModel::where('email', $email)->get();

        return $models->map(function ($model) {
            return new MissionEntity(
                id: $model->id,
                missionName: $model->mission_name,
                status: $model->status,
                startingLocation: $model->starting_location,
                destination: $model->destination,
                email: $model->email,
                dateCreated: new Date($model->date_created),
                dateDelivered: new Date($model->date_delivered)
            );
        })->all();
    }

    /**
     * Create a new mission in the database and return a domain entity
     *
     * @param MissionEntity $mission
     * @return MissionEntity
     */
    public function create(MissionEntity $mission): MissionEntity
    {
        $model = MissionModel::create([
            'mission_name' => $mission->missionName,
            'status'       => $mission->status,
            'starting_location' => $mission->startingLocation,
            'destination'   => $mission->destination,
            'email' => $mission->email ?? Auth::user()?->email,
            'date_created'  => $mission->dateCreated,
            'date_delivered' => $mission->dateDelivered
        ]);

        return new MissionEntity(
            id: $model->id,
            missionName: $model->mission_name,
            status: $model->status,
            startingLocation: $model->starting_location,
            destination: $model->destination,
            email: $model->email,
            dateCreated: $model->date_created,
            dateDelivered: $model->date_delivered
        );
    }
}
