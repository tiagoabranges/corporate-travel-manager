<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Support\ApiResponse;
use App\Support\StatusCode;

class AuthController extends Controller
{
    /**
     * @OA\Tag(
     *   name="Auth",
     *   description="Endpoints de autenticação"
     * )
     */

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login do usuário",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="admin@teste.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso"
     *     )
     * )
     */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = auth('api')->attempt($credentials)) {
            return ApiResponse::error('Unauthorized', StatusCode::UNAUTHORIZED);
        }

        return ApiResponse::success([
            'token' => $token,
            'user' => auth('api')->user()
        ]);
    }
  /**
         * @OA\Post(
         *   path="/register",
         *   summary="Registrar novo usuário",
         *   tags={"Auth"},
         *   @OA\RequestBody(
         *     required=true,
         *     @OA\JsonContent(
         *       required={"name","email","password"},
         *       @OA\Property(property="name", type="string"),
         *       @OA\Property(property="email", type="string"),
         *       @OA\Property(property="password", type="string")
         *     )
         *   ),
         *   @OA\Response(response=201, description="Usuário criado"),
         *   @OA\Response(response=422, description="Validação inválida")
         * )
         */
    public function register(Request $request)
    {
      
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return ApiResponse::success(
            $user,
            'Usuário criado',
            StatusCode::CREATED
        );
    }
  /**
         * @OA\Get(
         *   path="/me",
         *   summary="Informações do usuário autenticado",
         *   tags={"Auth"},
         *   security={{"bearerAuth":{}}},
         *   @OA\Response(response=200, description="Usuário logado"),
         *   @OA\Response(response=401, description="Unauthorized")
         * )
         */

    public function me()
    {
      
        return ApiResponse::success(auth('api')->user());
    }
  /**
         * @OA\Post(
         *   path="/logout",
         *   summary="Logout do usuário",
         *   tags={"Auth"},
         *   security={{"bearerAuth":{}}},
         *   @OA\Response(response=200, description="Logout realizado"),
         *   @OA\Response(response=401, description="Unauthorized")
         * )
         */

    public function logout()
    {
      
        auth('api')->logout();

        return ApiResponse::success([], 'Logout realizado');
    }
}
