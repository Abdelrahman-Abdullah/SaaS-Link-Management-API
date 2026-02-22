<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SessionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponseHelper;

class AuthController extends Controller
{
    use ApiResponseHelper;

    public function register(RegisterRequest $request)
    {
        try {
            User::create($request->validated());
            return $this->apiResponse(
                message: 'User registered successfully',
                status: 201
            );

        } catch (\Exception $e) {
            return $this->apiResponse(
                data: $e->getMessage(),
                message: 'User registration failed',
                status: $e->getCode() ?: 500
            );
        }

    }

    public function login(SessionRequest $request)
    {
        try {
            // Trying To login with user credentials and return token if successful
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                return $this->apiResponse(
                    message: 'Invalid email or password',
                    status: 'error',
                    code: 401
                );
            }
            $user = Auth::user();
            $token = $user->createToken('auth_token_'.$user->id)->plainTextToken;
            return $this->apiResponse(
                data: ['access_token' => $token, 'token_type' => 'Bearer'],
                message: 'Login successful',
            );

        } catch (\Exception $e) {

            return $this->apiResponse(
                data: $e->getMessage(),
                message: 'something went wrong during login',
                status: $e->getCode() ?: 500
            );
        }


    }

    public function logout(Request $request)
    {
        try {
            if (!$request->user() || !$request->user()->currentAccessToken()) {
                return $this->apiResponse(
                    message: 'No authenticated user found',
                    status: 'error',
                    code: 401
                );
            }
            $request->user()->currentAccessToken()->delete();
            return $this->apiResponse(
                message: 'Logout successful',
            );
        } catch (\Exception $e) {
            return $this->apiResponse(
                data: $e->getMessage(),
                message: 'something went wrong during logout',
                status: $e->getCode() ?: 500
            );
        }

    }

}
