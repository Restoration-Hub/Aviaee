<?php

namespace App\Domain\UseCases;

use App\Domain\Entities\MissionEntity;
use App\Domain\Interfaces\IMissionRepository;
use Illuminate\Support\Facades\Hash;

class UpdateMission
{
    public function __construct(
        private IMissionRepository $missions
    ) {}

    /**
     * Updates a mission status by ID
     */
    public function execute(int $id, string $status): void
    {
        $this->missions->updateStatus($id, $status);
    }
}
