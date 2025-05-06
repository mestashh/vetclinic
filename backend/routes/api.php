<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VeterinarianController;
use App\Http\Controllers\Api\AuthController;




Route::apiResource('clients', ClientController::class);
Route::apiResource('animals', PetController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('procedures', ServiceController::class);
Route::apiResource('veterinarians', VeterinarianController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

