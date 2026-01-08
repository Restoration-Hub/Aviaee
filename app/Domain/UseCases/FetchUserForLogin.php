<?php

namespace App\Domain\UseCases;

use App\Domain\Interfaces\IUserRepository;
use Illuminate\Support\Facades\Hash;

class FetchUserForLogin
{
    public function __construct(
        private IUserRepository $users
    ) {}

    /**
     * Returns the user ID if login succeeds, null otherwise
     */
    public function execute(string $email, string $password): ?int
    {
        $data = $this->users->findByEmail($email); // Returns ['id', 'password'] or null

        if (!$data) {
            return null;
        }

        if (!Hash::check($password, $data['password'])) {
            return null;
        }

        return $data['id'];
    }
}

