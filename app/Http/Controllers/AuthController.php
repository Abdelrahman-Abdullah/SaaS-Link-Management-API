<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create($request->validated());
        return response()->json(['message' => 'User registered successfully'], 201);

    }

    public function login(Request $request)
    {
        // Login logic would go here
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