<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelOrderController;

Route::get('/orders', [TravelOrderController::class, 'index']);
Route::post('/orders', [TravelOrderController::class, 'store']);
Route::get('/orders/{id}', [TravelOrderController::class, 'show']);
Route::patch('/orders/{id}/status', [TravelOrderController::class, 'updateStatus']);