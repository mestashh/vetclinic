<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VeterinarianController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Здесь регистрируются все маршруты для API вашего приложения.
| Все они автоматически получат префикс /api.
|
*/

// CRUD для клиентов
Route::apiResource('clients', ClientController::class);

// CRUD для животных
Route::apiResource('animals', PetController::class);

// CRUD для приёмов
Route::apiResource('appointments', AppointmentController::class);

// CRUD для процедур
Route::apiResource('procedures', ServiceController::class);

// CRUD для медикаментов
Route::apiResource('medications', VeterinarianController::class);
