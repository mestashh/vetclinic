<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VeterinarianController;





// Клиенты — JSON
Route::get('/clients/{client_id}/pets', [ClientController::class, 'pets']);
Route::get('/clients', [ClientController::class, 'index']);
Route::get('/veterinarians', [VeterinarianController::class, 'index']);

// Питомцы, приёмы, услуги, ветеринары
Route::apiResource('animals',      PetController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('procedures',   ServiceController::class);
Route::apiResource('veterinarians',VeterinarianController::class);

// Аутентификация
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('api.logout');
