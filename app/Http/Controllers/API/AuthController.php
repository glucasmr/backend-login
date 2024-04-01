<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *    title="Backend Login API",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )

 */
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $AuthService)
    {
        $this->authService = $AuthService;
    }

    /**
     * @OA\Post(
     *     tags={"Autenticação"}, 
     *     path="/register",
     *     summary="Registra um novo usuário",
     *     description="Cria um novo usuário com as informações fornecidas",
     *     operationId="register",
     *     @OA\RequestBody(
     *        description="Dados do usuário",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *    @OA\Response(
     *       response=400,
     *      description="Erro de validação"
     *   )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = $request->validated();
        $data = $this->authService->register($user);
        return (new UserResource([
            'user' => $data['user'],
            'message' => 'User registered successfully!',
            'token' => $data['token']
        ]))
            ->response()->setStatusCode(201);
    }

    /**
     * @OA\Post(
     *     tags={"Autenticação"},
     *     path="/login",
     *     summary="Autentica um usuário",
     *    description="Autentica um usuário com as credenciais fornecidas",
     *    operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Detalhes de login inválidos",
     *     )
     * )
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
            'token' => $data['token']
        ]))
            ->response()->setStatusCode(200);
    }

    /**
     * @OA\Post(
     *     tags={"Autenticação"},
     *     path="/logout",
     *     summary="Desconecta o usuário atualmente autenticado",
     *    description="Desconecta o usuário atualmente autenticado",
     *   operationId="logout",
     *    security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Desconectado com sucesso",
     *     ),
     *    @OA\Response(
     *        response=401,
     *       description="Não autorizado"
     *   )
     * )
     */
    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logged out successfully!'], 200);
    }

    /**
     * @OA\Get(
     *     tags={"Autenticação"},
     *     path="/info",
     *     summary="Obtém informações sobre o usuário atualmente autenticado",
     *     description="Obtém informações sobre o usuário atualmente autenticado",
     *    operationId="info",
     *   security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informações do usuário",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
*          )
     * )
     */
    public function info()
    {
        return (new UserResource(['user' => Auth::user()]))->response()->setStatusCode(200);
    }
}
