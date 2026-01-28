<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\MissionEntity;

interface IMissionRepository
{
    /**
     * Creates a mission in the database and returns the new entity
     */
    public function create(MissionEntity $mission): MissionEntity;
}
