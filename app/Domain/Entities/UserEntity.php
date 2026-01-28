<?php

namespace App\Domain\Entities;

class UserEntity
{
    public function __construct(
        public ?int $id,
        public string $firstName,
        public string $lastName,
        public string $phoneNumber,
        public string $userType,
        public string $email,
        public string $password,
    ) {}

    public function initials(): string
    // {
    //     return collect(explode(' ', $this->name))
    //         ->map(fn ($n) => $n[0])
    //         ->implode('');
    // }
    {
        return strtoupper(
            $this->firstName[0] . $this->lastName[0]
        );
    }
}

