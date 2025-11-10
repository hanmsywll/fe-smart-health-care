<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StatusPageController;
use App\Http\Controllers\JanjiPageController;

Route::get('/', function () {
    return view('home');
});

Route::get('/tailwind', function () {
    return view('tailwind');
});

Route::get('/users', [UsersController::class, 'index']);
Route::get('/status', [StatusPageController::class, 'index']);
Route::get('/ketersediaan', [JanjiPageController::class, 'index']);
Route::view('/about', 'about');
Route::view('/lokasi', 'lokasi');
