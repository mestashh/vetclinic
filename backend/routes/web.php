<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/clients', function () {
    return view('clients');
})->name('clients.index');

Route::get('/appointments', function () {
    return view('appointments');
})->name('appointments.index');
