<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StatusPageController;
use App\Http\Controllers\JanjiPageController;
use App\Http\Controllers\JanjiTemuController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
});

Route::get('/tailwind', function () {
    return view('tailwind');
});

Route::get('/users', [UsersController::class, 'index']);
Route::get('/status', [StatusPageController::class, 'index']);
Route::get('/ketersediaan', [JanjiPageController::class, 'index'])->middleware('gateway.auth');
Route::view('/about', 'about');
Route::view('/lokasi', 'lokasi');
Route::view('/dashboard', 'dashboard');

Route::post('/janji/booking-cepat', [JanjiTemuController::class, 'bookingCepat']);
Route::get('/janji', [JanjiTemuController::class, 'getAllJanjiTemu']);
Route::get('/janji/search', [JanjiTemuController::class, 'searchJanjiTemu']);
Route::get('/janji/{id}', [JanjiTemuController::class, 'getJanjiTemuById']);
Route::put('/janji/{id}', [JanjiTemuController::class, 'updateJanjiTemu']);
Route::delete('/janji/{id}', [JanjiTemuController::class, 'deleteJanjiTemu']);

// Auth (login sederhana)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/session/token', [AuthController::class, 'syncToken'])->name('session.token');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
