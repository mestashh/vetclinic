<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PageController;


Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/news', [PageController::class, 'news'])->name('news');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/services', [PageController::class, 'services'])->name('services');
    Route::get('/pet-history/{id}', function ($id) {
        return view('pages.pet-history', compact('id'));
    })->name('pet-history');
});

Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/about', [PageController::class, 'aboutMe'])->name('about');
    Route::post('/profile/update', [PageController::class, 'updateProfileUser'])->name('profile.update');
    Route::post('/pets/store', [PageController::class, 'storePet'])->name('pets.store');
    Route::get('/my-appointments', [PageController::class, 'myAppointments'])->name('my-appointments');
    Route::get('/my-past-appointments', [PageController::class, 'pastAppointments'])->name('my-past-appointments');
});

Route::middleware(['auth', 'role:vet'])->group(function () {
    Route::get('/appointments/start/{appointment}', [PageController::class, 'startAppointment'])->name('appointments.start');
    Route::get('/appointments/start', [PageController::class, 'selectAppointment'])->name('appointments.select');
});

Route::middleware(['auth', 'role:vet,admin,superadmin'])->group(function () {
    Route::get('/appointments', [PageController::class, 'appointments'])->name('appointments');
});

Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::get('/users', [PageController::class, 'users'])->name('users');
    Route::get('/pets', [PageController::class, 'pets'])->name('pets');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::view('/orders', 'pages.orders')->name('orders');
    Route::get('/veterinarians', [PageController::class, 'veterinarians'])->name('veterinarians');
    Route::get('/change-roles', function () {
        return view('pages.change-roles');
    })->name('change-roles');
});
