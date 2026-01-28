<?php

namespace App\Domain\UseCases;

use App\Domain\Entities\MissionEntity;
use App\Domain\Interfaces\IMissionRepository;
use Illuminate\Support\Facades\Hash;

class CreateMission
{
    public function __construct(
        private IMissionRepository $missions
    ) {}

    /**
     * Creates a new mission and returns the mission ID
     */
    public function execute(MissionEntity $mission): int
    {
        $createdMission = $this->missions->create($mission);

    }
        return $createdMission->id;

    }