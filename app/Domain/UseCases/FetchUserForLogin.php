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
   public function execute(string $email, string $password): array {
        $data = $this->users->findByEmail($email);

        if (!$data) {
            return [
                'status' => 'invalid_credentials',
                'user_id' => null,
            ];
        }

        if (!Hash::check($password, $data['password'])) {
            return [
                'status' => 'invalid_credentials',
                'user_id' => null,
            ];
        }

        if (!$data['email_verified_at']) {
            return [
                'status' => 'unverified',
                'user_id' => $data['id'],
            ];
        }

        return [
            'status' => 'success',
            'user_id' => $data['id'],
        ];
    }
}

