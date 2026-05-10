<?php

// routes/api.php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\DistributionController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\DonorController;
use App\Http\Controllers\Api\VolunteerController;

// Auth (publik)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Donasi publik (bisa dilihat tanpa login)
Route::get('/donations', [DonationController::class, 'index']);

// Route yang membutuhkan autentikasi
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Donatur
    Route::post('/donations', [DonationController::class, 'store']);
    Route::get('/donor/dashboard', [DonorController::class, 'dashboard']);
    Route::get('/donor/donations', [DonorController::class, 'myDonations']);
    Route::post('/donations/{id}', [DonationController::class, 'update']);
    Route::delete('/donations/{id}', [DonationController::class, 'destroy']);

    // Relawan
    Route::post('/claim/{donation_id}', [ClaimController::class, 'claim']);
    Route::get('/volunteer/dashboard', [VolunteerController::class, 'dashboard']);

    // Distribusi (relawan)
    Route::post('/distributions', [DistributionController::class, 'store']);
});