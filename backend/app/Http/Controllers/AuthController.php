<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Support\ApiResponse;
use App\Support\StatusCode;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return ApiResponse::error('Unauthorized', StatusCode::UNAUTHORIZED);
        }

        return ApiResponse::success([
            'token' => $token,
            'user' => auth()->user()
        ]);
    }

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

    public function me()
    {
        return ApiResponse::success(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return ApiResponse::success([], 'Logout realizado');
    }
}
