<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Главная страница
Route::get('/', function () {
    return view('home');
})->name('home');

// Клиенты
Route::get('/clients', function () {
    return view('clients');
})->name('clients.index');

// Записи на приём
Route::get('/appointments', function () {
    return view('appointments');
})->name('appointments.index');

// Авторизация и регистрация
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Панель администратора (защищена middleware)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Панель клиента (защищена middleware)
Route::middleware(['auth:sanctum', 'client'])->group(function () {
    Route::get('/client/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');
});

// Панель ветеринара (защищена middleware)
Route::middleware(['auth:sanctum', 'veterinarian'])->group(function () {
    Route::get('/veterinarian/dashboard', function () {
        return view('veterinarian.dashboard');
    })->name('veterinarian.dashboard');
});
