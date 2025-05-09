<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VeterinarianController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ServiceItemController;



Route::apiResource('services.items', ServiceItemController::class)->shallow();
Route::apiResource('items', ServiceItemController::class)->only(['update', 'destroy']);







Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
Route::get('/news', [NewsController::class, 'index']);
Route::post('/news', [NewsController::class, 'store']);
Route::get('/veterinarians/{veterinarian}/appointments/{date}', [AppointmentController::class, 'busySlots']);
Route::get('/veterinarians/by-user/{user}', [VeterinarianController::class, 'byUser']);
Route::get('/users/{user}/pets', [UserController::class, 'pets']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/veterinarians', [VeterinarianController::class, 'index']);
Route::apiResource('users', UserController::class);
Route::apiResource('animals', PetController::class)->parameters([
    'animals' => 'pet'
]);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('veterinarians',VeterinarianController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('api.logout');
