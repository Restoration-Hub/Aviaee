<?php

namespace App\Domain\Entities;

class MissionEntity
{
    public function __construct(
        public ?int $id,
        public string $missionName,
        public string $status, // TODO: Change to Enum?
        public string $startingLocation,
        public string $destination,
        public string $email,
        public Date $dateCreated,
        public Date $dateDelivered
    ) {}
}

