<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\UseCases\FetchUserForLogin;

class VerifyLoginCredentialsController extends Controller
{
    private FetchUserForLogin $fetchUser;

    // Inject the use case
    public function __construct(FetchUserForLogin $fetchUser)
    {
        $this->fetchUser = $fetchUser;
    }

    /**
     * Handle login request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            // Call the use case
            $userId = $this->fetchUser->execute(
                $request->input('email'),
                $request->input('password')
            );

            // Return response
            if (!$userId) {
                return response()->json([
                    'message' => 'Invalid email or password.'
                ], 401);
            }

            return response()->json([
                'message' => 'Login successful',
                'user_id' => $userId
            ], 200);

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
