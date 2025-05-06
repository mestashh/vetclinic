<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VeterinarianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Гостевые
Route::get('/', fn() => view('home'))->name('home');
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');

// CRUD для всех сущностей
Route::middleware('auth')->group(function () {
    // Клиенты
    Route::get   ('/clients',            [ClientController::class,      'index']  )->name('clients');
    Route::post  ('/clients',            [ClientController::class,      'store']  )->name('clients.store');
    Route::put   ('/clients/{client}',   [ClientController::class,      'update'] )->name('clients.update');
    Route::delete('/clients/{client}',   [ClientController::class,      'destroy'])->name('clients.destroy');

    // Питомцы
    Route::get   ('/pets',               [PetController::class,         'index']  )->name('pets');
    Route::post  ('/pets',               [PetController::class,         'store']  )->name('pets.store');
    Route::put   ('/pets/{pet}',         [PetController::class,         'update'] )->name('pets.update');
    Route::delete('/pets/{pet}',         [PetController::class,         'destroy'])->name('pets.destroy');

    // Приёмы
    Route::get   ('/appointments',       [AppointmentController::class, 'index']  )->name('appointments');
    Route::post  ('/appointments',       [AppointmentController::class, 'store']  )->name('appointments.store');
    Route::put   ('/appointments/{appointment}', [AppointmentController::class, 'update'] )->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Услуги
    Route::get   ('/services',           [ServiceController::class,     'index']  )->name('services');
    Route::post  ('/services',           [ServiceController::class,     'store']  )->name('services.store');
    Route::put   ('/services/{service}', [ServiceController::class,     'update'] )->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class,     'destroy'])->name('services.destroy');

    // Ветеринары
    Route::get   ('/veterinarians',      [VeterinarianController::class, 'index']  )->name('veterinarians');
    Route::post  ('/veterinarians',      [VeterinarianController::class, 'store']  )->name('veterinarians.store');
    Route::put   ('/veterinarians/{veterinarian}', [VeterinarianController::class, 'update'] )->name('veterinarians.update');
    Route::delete('/veterinarians/{veterinarian}', [VeterinarianController::class, 'destroy'])->name('veterinarians.destroy');

    // Выход
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
