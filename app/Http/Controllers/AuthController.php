<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SessionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create($request->validated());
        return response()->json(['message' => 'User registered successfully'], 201);

    }

    public function login(SessionRequest $request)
    {
    
        // Tring To login with user credentials and return token if successful
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token_'.$user->id)->plainTextToken;
                return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
            }
            return response()->json(['message' => 'Invalid credentials'], 401);

    }

    public function logout(Request $request)
    {
        // Logout logic would go here
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}