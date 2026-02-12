<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelOrderController;

Route::apiResource('travel-orders', TravelOrderController::class);
Route::patch(
    'travel-orders/{id}/status',
    [TravelOrderController::class,'updateStatus']
);
