<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Все маршруты веб-интерфейса автоматически получают middleware 'web'
*/

// 1) Гостевые страницы: главная, вход/регистрация
Route::get('/', fn() => view('home'))->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 2) Защищённая зона — только для авторизованных
Route::middleware('auth')->group(function () {
    // 2.1) Статические страницы через Blade
    Route::view('/clients',       'layouts.clients')->name('clients');
    Route::view('/appointments',  'layouts.appointments')->name('appointments');
    Route::view('/pets',          'layouts.pets')->name('pets');
    Route::view('/services',      'layouts.services')->name('services');
    Route::view('/veterinarians', 'layouts.veterinarians')->name('veterinarians');

    // 2.2) Дашборды
    Route::view('/admin/dashboard',        'layouts.admin')->name('admin.dashboard');
    Route::view('/client/dashboard',       'client.dashboard')->name('client.dashboard');
    Route::view('/veterinarian/dashboard','veterinarian.dashboard')->name('veterinarian.dashboard');

    // 2.3) Выход из системы
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
