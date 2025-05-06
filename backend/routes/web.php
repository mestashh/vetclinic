<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\PageController;

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
    Route::get('/clients',       [PageController::class, 'clients'])->name('clients');
    Route::get('/pets',          [PageController::class, 'pets'])->name('pets');
    Route::get('/appointments',  [PageController::class, 'appointments'])->name('appointments');
    Route::get('/services',      [PageController::class, 'services'])->name('services');
    Route::get('/veterinarians', [PageController::class, 'veterinarians'])->name('veterinarians');

    // 2.2) Дашборды
    Route::view('/client/dashboard',       'client.dashboard')->name('client.dashboard');
    Route::view('/veterinarian/dashboard','veterinarian.dashboard')->name('veterinarian.dashboard');

    // 2.3) Выход из системы
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
