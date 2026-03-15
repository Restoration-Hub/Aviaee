<?php

namespace App\Domain\UseCases;

use App\Domain\Entities\MissionEntity;
use App\Domain\Interfaces\IMissionRepository;
use Illuminate\Support\Facades\Hash;

class DeleteMission
{
    public function __construct(
        private IMissionRepository $missions
    ) {}

    /**
     * Deletes a mission by ID
     */
    public function execute(int $id): void
    {
        $this->missions->delete($id);
    }
}
