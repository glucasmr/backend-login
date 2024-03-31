<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $AuthService)
    {
        $this->authService = $AuthService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $request->validated();
        $data = $this->authService->register($user);
        return (new UserResource([
            'user' => $data['user'],
            'message' => 'User registered successfully!', 
            'token' => $data['token']]))
        ->response()->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        $login = $request->validated();
        $data = $this->authService->login($login);

        if (!$data) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        return (new UserResource([
            'user' => $data['user'],
            'message' => 'Login successful!', 
            'token' => $data['token']]))
        ->response()->setStatusCode(200);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logged out successfully!'], 200);
    }

    public function info()
    {
        return (new UserResource(['user' => Auth::user()]))->response()->setStatusCode(200);
    }
}
