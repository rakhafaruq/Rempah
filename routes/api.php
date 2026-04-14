<?php

// routes/api.php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\DistributionController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\DonorController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/donations', [DonationController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/donations', [DonationController::class, 'store']);
    
    Route::post('/claim/{donation_id}', [ClaimController::class, 'claim']);

    Route::post('/distributions', [DistributionController::class, 'store']);

    Route::get('/donor/dashboard', [DonorController::class, 'dashboard']);
});