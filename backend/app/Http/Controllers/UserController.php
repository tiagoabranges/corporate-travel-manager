<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\ApiResponse;
use App\Support\StatusCode;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
        ]);

        if (empty($data)) {
            return ApiResponse::error(
                'Nenhum campo enviado para atualização',
                StatusCode::UNPROCESSABLE_ENTITY
            );
        }

        $user->update($data);

        return ApiResponse::success(
            $user,
            'Perfil atualizado com sucesso'
        );
    }
}
