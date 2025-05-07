<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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
    // Blade-страницы через PageController
    Route::get('/clients', [PageController::class, 'clients'])->name('clients');
    Route::get('/pets', [PageController::class, 'pets'])->name('pets');
    Route::get('/appointments', [PageController::class, 'appointments'])->name('appointments');
    Route::get('/services', [PageController::class, 'services'])->name('services');
    Route::get('/veterinarians', [PageController::class, 'veterinarians'])->name('veterinarians');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
