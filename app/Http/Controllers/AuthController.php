<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($user->username . "'s " . 'auth_token')->plainTextToken;

        return new UserResource(['user' => $user,'message' => 'User registered successfully!', 'token' => $token]);
    }

    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $user = Auth::user();
        Auth::user()->tokens()->delete();
        $token = $user->createToken($user->username . "'s " . 'auth_token')->plainTextToken;

        return new UserResource(['user' => Auth::user(),'message' => 'Login successful!', 'token' => $token]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out successfully!'
        ]);
    }

    public function info()
    {
        return new UserResource(['user' => Auth::user()]);
    }
}
