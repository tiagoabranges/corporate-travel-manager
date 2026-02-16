<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {

    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Atualizar perfil
    Route::put('/me', [UserController::class, 'updateProfile']);

    // Travel Orders
    Route::apiResource('travel-orders', TravelOrderController::class);
    Route::patch(
        'travel-orders/{id}/status',
        [TravelOrderController::class, 'updateStatus']
    );
});

Route::fallback(function () {
    return response()->json([
        'error' => true,
        'message' => 'Rota não encontrada'
    ], 404);
});
