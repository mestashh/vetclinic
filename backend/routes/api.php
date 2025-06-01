<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServiceItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\VeterinarianController;
use App\Http\Controllers\Api\NewsController;

// Public authentication endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected API routes via Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'destroy']);

    Route::get('/news',  [NewsController::class, 'index']);
    Route::post('/news', [NewsController::class, 'store']);

    Route::get('/veterinarians/{veterinarian}/appointments/{date}', [AppointmentController::class, 'busySlots']);
    Route::get('/veterinarians/by-user/{user}', [VeterinarianController::class, 'byUser']);
    Route::apiResource('veterinarians', VeterinarianController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/users/{user}/pets', [UserController::class, 'pets']);
    Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
    Route::apiResource('users', UserController::class);

    Route::apiResource('animals', PetController::class)->parameters(['animals' => 'pet']);

    Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete']);
    Route::apiResource('appointments', AppointmentController::class);

    Route::apiResource('services', ServiceController::class);
    Route::apiResource('services.items', ServiceItemController::class)->shallow();
    Route::apiResource('items', ServiceItemController::class)->only(['update', 'destroy']);
});

