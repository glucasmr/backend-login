<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

/**
 * The AuthController class handles user authentication and registration.
 *
 */
class AuthController extends Controller
{
    /**
     * The authentication service instance.
     *
     * @var AuthService
     */
    protected $authService;

    /**
     * Create a new AuthController instance.
     *
     * @param AuthService $AuthService The authentication service instance.
     * @return void
     */
    public function __construct(AuthService $AuthService)
    {
        $this->authService = $AuthService;
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request The register request instance.
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Log in a user.
     *
     * @param LoginRequest $request The login request instance.
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Log out the currently authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logged out successfully!'], 200);
    }

    /**
     * Get information about the currently authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        return (new UserResource(['user' => Auth::user()]))->response()->setStatusCode(200);
    }
}
