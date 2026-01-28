<?php

namespace App\Repositories;

use App\Domain\Interfaces\IUserRepository;
use App\Domain\Entities\UserEntity;
use App\Models\User as UserModel;

class UserRepository implements IUserRepository
{
    /**
     * Find a user by email for login
     *
     * @param string $email
     * @return array{id: int, password: string}|null
     */
    public function findByEmail(string $email): ?array
    {
        $user = UserModel::where('email', $email)->first();

        return $user ? [
            'id' => $user->id,
            'password' => $user->password,
        ] : null;
    }

    /**
     * Create a new user in the database and return a domain entity
     *
     * @param UserEntity $user
     * @param string $hashedPassword
     * @return UserEntity
     */
    public function create(UserEntity $user, string $hashedPassword): UserEntity
    {
        $model = UserModel::create([
            'first_name'   => $user->firstName,
            'last_name'    => $user->lastName,
            'phone_number' => $user->phoneNumber,
            'user_type'    => $user->userType,
            'email'        => $user->email,
            'password'     => $hashedPassword,
        ]);

        return new UserEntity(
            id: $model->id,
            firstName: $model->first_name,
            lastName: $model->last_name,
            phoneNumber: $model->phone_number,
            userType: $model->user_type,
            email: $model->email,
            password: $model->password,
        );
    }
}
