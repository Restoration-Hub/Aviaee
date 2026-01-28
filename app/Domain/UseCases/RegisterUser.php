<?php

namespace App\Domain\UseCases;

use App\Domain\Entities\UserEntity;
use App\Domain\Interfaces\IUserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    public function __construct(
        private IUserRepository $users
    ) {}

    /**
     * Registers a new user and returns the user ID
     */
    public function execute(UserEntity $user, string $plainPassword): int
    {
        $hashedPassword = Hash::make($plainPassword);

        $createdUser = $this->users->create(
            user: $user,
            hashedPassword: $hashedPassword
        );

        return $createdUser->id;
    }
}
