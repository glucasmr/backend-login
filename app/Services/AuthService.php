<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register($data)
    {
        $user = $this->userRepository->create($data);
        $token = $user->createToken($user->username . "'s auth_token")->plainTextToken;
        return ['user' => $user, 'token' => $token];
    }

    public function login($credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $this->userRepository->revokeToken($user);
        $token = $user->createToken($user->username . "'s auth_token")->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout()
    {
        $this->userRepository->revokeToken(Auth::user());
    }
}
