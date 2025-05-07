<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\VeterinarianController;
use App\Http\Controllers\PageController;





// Авторизация и регистрация
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/clients', [ClientController::class, 'showClientsPage'])->name('clients');
    Route::get('/pets', [PetController::class, 'showPetsPage'])->name('pets');
    Route::get('/appointments', [AppointmentController::class, 'showAppointmentsPage'])->name('appointments');
    Route::get('/services', [ServiceController::class, 'showServicesPage'])->name('services');
    Route::get('/appointments', [PageController::class, 'showAppointmentsPage'])->name('appointments');
    Route::get('/veterinarians', [VeterinarianController::class, 'showVeterinariansPage'])->name('veterinarians');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
