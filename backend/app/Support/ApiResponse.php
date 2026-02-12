<?php

namespace App\Support;

class ApiResponse
{
    public static function success($data = [], $message = 'OK', $code = StatusCode::OK)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error($message = 'Erro', $code = StatusCode::SERVER_ERROR)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
