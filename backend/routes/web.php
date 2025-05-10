<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PageController;







Route::get('/my-past-appointments', [PageController::class, 'pastAppointments'])->name('my-past-appointments');
Route::get('/pet-history/{id}', function ($id) {
    return view('pages.pet-history', compact('id'));
})->name('pet-history');






Route::get('/appointments/start/{appointment}', [PageController::class, 'startAppointment'])->name('appointments.start');
Route::get('/appointments/start', [PageController::class, 'selectAppointment'])->name('appointments.select');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/news', [PageController::class, 'news'])->name('news');

Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/change-roles', function () {
    return view('pages.change-roles');
})->name('change-roles');

Route::middleware('auth')->group(function () {
    Route::get('/my-appointments', [PageController::class, 'myAppointments'])->name('my-appointments');
    Route::get('/users', [PageController::class, 'users'])->name('users');
    Route::get('/veterinarians', [PageController::class, 'veterinarians'])->name('veterinarians');
    Route::get('/pets', [PageController::class, 'pets'])->name('pets');
    Route::get('/appointments', [PageController::class, 'appointments'])->name('appointments');
    Route::get('/services', [PageController::class, 'services'])->name('services');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/about', [PageController::class, 'aboutMe'])->name('about');
    Route::post('/profile/update', [PageController::class, 'updateProfileUser'])->name('profile.update');
    Route::post('/pets/store', [PageController::class, 'storePet'])->name('pets.store');

});
