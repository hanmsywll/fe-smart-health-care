<?php

use Illuminate\Support\Facades\Route;
use App\Services\ApiGateway;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| File ini memuat rute API untuk aplikasi FE Smart Health Care.
| Rute bersifat ringan dan dapat mem-proxy ke API Gateway bila diperlukan.
| Tambahkan rute baru di sini jika Anda membutuhkan endpoint JSON lokal.
|
*/

// Health check sederhana
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'time' => now()->toIso8601String(),
    ]);
});

// Proxy status ke API Gateway (opsional, berguna untuk diagnosa cepat)
Route::get('/status', function (ApiGateway $api) {
    try {
        $data = $api->get('/status');
        return response()->json($data);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
});