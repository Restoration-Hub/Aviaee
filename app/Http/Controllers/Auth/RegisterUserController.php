<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\UseCases\RegisterUser;
use App\Domain\Entities\UserEntity;
use Throwable;

class RegisterUserController extends Controller
{
    private RegisterUser $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function __invoke(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'first_name'   => 'required|string',
            'last_name'    => 'required|string',
            'phone_number' => 'required|string',
            'user_type'    => 'required|string',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:8',
        ]);

        try {
            // Create UserEntity from request data
            $userEntity = new UserEntity(
                id: null,
                firstName: $request->input('first_name'),
                lastName: $request->input('last_name'),
                phoneNumber: $request->input('phone_number'),
                userType: $request->input('user_type'),
                email: $request->input('email'),
                password: '' // handled in use case
            );

            // Call the use case
            $userId = $this->registerUser->execute(
                $userEntity,
                $request->input('password')
            );

            // Success response
            return response()->json([
                'message' => 'Registration successful',
                'user_id' => $userId
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
