<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ServiceController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/clients', function () {
    return view('clients');
})->name('clients.index');

Route::get('/appointments', function () {
    return view('appointments');
})->name('appointments.index');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/pets', [PetController::class, 'index'])->name('pets.index');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/client/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');

    Route::get('/veterinarian/dashboard', function () {
        return view('veterinarian.dashboard');
    })->name('veterinarian.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
