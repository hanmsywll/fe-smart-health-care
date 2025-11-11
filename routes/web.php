<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StatusPageController;
use App\Http\Controllers\JanjiPageController;
use App\Http\Controllers\JanjiTemuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalRecordController;

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

// Medical Records (basic CRUD)
Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
Route::get('/medical-records/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
Route::get('/medical-records/{id}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
Route::put('/medical-records/{id}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
Route::delete('/medical-records/{id}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
