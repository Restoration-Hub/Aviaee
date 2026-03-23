<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\UseCases\FetchUserForLogin;
use Illuminate\Support\Facades\Auth;

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

            $result = $this->fetchUser->execute(
                $request->input('email'),
                $request->input('password')
            );

            // Return response
            if ($result['status'] === 'invalid_credentials') {
                return response()->json([
                    'message' => 'Invalid email or password.'
                ], 401);
            }

            if ($result['status'] === 'unverified') {
                return response()->json([
                    'message' => 'Please verify your email before logging in.',
                ], 403);
            }

            // Log the user in so subsequent requests are authenticated
            Auth::loginUsingId($result['user_id']);
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful',
                'user_id' => $result['user_id']
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
