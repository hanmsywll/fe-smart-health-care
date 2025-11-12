<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
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

// Booking janji sesuai spesifikasi: POST /janji
Route::post('/janji', [JanjiTemuController::class, 'bookingCepat']);
Route::post('/janji/booking-cepat', [JanjiTemuController::class, 'bookingCepat']);
Route::get('/janji', [JanjiTemuController::class, 'getAllJanjiTemu']);
Route::get('/janji/search', [JanjiTemuController::class, 'searchJanjiTemu']);
Route::get('/janji/{id}', [JanjiTemuController::class, 'getJanjiTemuById']);
Route::put('/janji/{id}', [JanjiTemuController::class, 'updateJanjiTemu']);
Route::delete('/janji/{id}', [JanjiTemuController::class, 'deleteJanjiTemu']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/session/token', [AuthController::class, 'syncToken'])->name('session.token');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register/pasien', [AuthController::class, 'registerPasien']);
Route::post('/register/dokter', [AuthController::class, 'registerDokter']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// // Medical Records (basic CRUD)
// Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
// Route::get('/medical-records/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
// Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
// Route::get('/medical-records/{id}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
// Route::put('/medical-records/{id}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
// Route::delete('/medical-records/{id}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');

Route::get('/rekam-medis', function () {
    return view('rekammedis');
})->name('rekam-medis');

Route::get('/debug/time', function () {
    $appTz = config('app.timezone');
    $phpTz = \date_default_timezone_get();
    $nowUtc = Carbon::now('UTC');
    $nowApp = Carbon::now($appTz);

    $fmtId = $nowApp->locale('id_ID')->translatedFormat('d/m/Y H.i');

    return response()->json([
        'server_time_utc' => $nowUtc->toIso8601String(),
        'server_time_app_tz' => $nowApp->toIso8601String(),
        'server_time_app_fmt_id' => $fmtId . ' WIB',
        'app_timezone' => $appTz,
        'php_timezone' => $phpTz,
        'unix_timestamp' => $nowUtc->timestamp,
        'offset_minutes_app' => $nowApp->offset / 60,
    ]);
});
