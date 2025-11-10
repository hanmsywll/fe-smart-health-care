<?php

namespace App\Http\Controllers;

use App\Services\ApiGateway;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class JanjiTemuController extends Controller
{
    protected ApiGateway $gateway;

    public function __construct(ApiGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Proxy booking cepat ke API Gateway external.
     * Repo ini hanya consume API, tanpa logika domain lokal.
     */
    public function bookingCepat(Request $request): JsonResponse
    {
        $payload = $request->only(['id_dokter', 'tanggal', 'waktu_mulai', 'keluhan']);
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
                'timestamp' => now()->toISOString(),
            ], 401);
        }

        try {
            $this->gateway->setToken($token);
            $result = $this->gateway->post('janji/booking-cepat', $payload);
            return response()->json($result, 201);
        } catch (RequestException $e) {
            $resp = $e->response;
            $status = $resp ? $resp->status() : 500;
            $body = $resp ? ($resp->json() ?? ['message' => $e->getMessage()]) : ['message' => $e->getMessage()];
            return response()->json($body, $status);
        } catch (\Throwable $e) {
            Log::error('Booking cepat error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan saat membooking janji temu',
                'debug' => config('app.debug') ? $e->getMessage() : null,
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Ambil semua janji temu dari API Gateway untuk kebutuhan dashboard.
     */
    public function getAllJanjiTemu(Request $request): JsonResponse

    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'timestamp' => now()->toISOString(),
            ], 401);
        }

        try {
            $this->gateway->setToken($token);
            $result = $this->gateway->get('janji');

            if (!is_array($result)) {

                $result = ['success' => true, 'data' => $result];
            } elseif (!array_key_exists('success', $result)) {
                $result['success'] = true;
            }
            return response()->json($result, 200);
        } catch (RequestException $e) {
            $resp = $e->response;
            $status = $resp ? $resp->status() : 500;
            $body = $resp ? ($resp->json() ?? ['message' => $e->getMessage()]) : ['message' => $e->getMessage()];
            return response()->json($body, $status);
        } catch (\Throwable $e) {

            Log::error('Get all janji temu error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan saat mengambil data janji temu',
                'debug' => config('app.debug') ? $e->getMessage() : null,
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Pencarian janji temu berdasarkan tanggal atau nama dokter.
     */
    public function searchJanjiTemu(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'timestamp' => now()->toISOString(),
            ], 401);
        }

        try {
            $tanggal = $request->query('tanggal');
            $namaDokter = $request->query('nama_dokter');

            $this->gateway->setToken($token);
            $result = $this->gateway->get('janji/search', [
                'tanggal' => $tanggal,
                'nama_dokter' => $namaDokter,
            ]);

            if (!is_array($result)) {
                $result = ['success' => true, 'data' => $result];
            } elseif (!array_key_exists('success', $result)) {
                $result['success'] = true;
            }

            return response()->json($result, 200);
        } catch (RequestException $e) {
            $resp = $e->response;
            $status = $resp ? $resp->status() : 500;
            $body = $resp ? ($resp->json() ?? ['message' => $e->getMessage()]) : ['message' => $e->getMessage()];
            return response()->json($body, $status);
        } catch (\Throwable $e) {
            \Log::error('Search janji temu error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan saat mencari janji temu',
                'debug' => config('app.debug') ? $e->getMessage() : null,
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Ambil detail janji temu berdasarkan ID.
     */
    public function getJanjiTemuById(Request $request, $id): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'timestamp' => now()->toISOString(),
            ], 401);
        }

        try {
            $this->gateway->setToken($token);
            $result = $this->gateway->get('janji/' . urlencode($id));

            if (!is_array($result)) {
                $result = ['success' => true, 'data' => $result];
            } elseif (!array_key_exists('success', $result)) {
                $result['success'] = true;
            }

            return response()->json($result, 200);
        } catch (RequestException $e) {
            $resp = $e->response;
            $status = $resp ? $resp->status() : 500;
            $body = $resp ? ($resp->json() ?? ['message' => $e->getMessage()]) : ['message' => $e->getMessage()];
            return response()->json($body, $status);
        } catch (\Throwable $e) {
            \Log::error('Get janji temu by id error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan saat mengambil data janji temu',
                'debug' => config('app.debug') ? $e->getMessage() : null,
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Update janji temu berdasarkan ID.
     */
    public function updateJanjiTemu(Request $request, $id): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'timestamp' => now()->toISOString(),
            ], 401);
        }

        try {
            $payload = $request->all();
            $this->gateway->setToken($token);
            $result = $this->gateway->put('janji/' . urlencode($id), $payload);

            if (!is_array($result)) {
                $result = ['success' => true, 'data' => $result];
            } elseif (!array_key_exists('success', $result)) {
                $result['success'] = true;
            }

            return response()->json($result, 200);
        } catch (RequestException $e) {
            $resp = $e->response;
            $status = $resp ? $resp->status() : 500;
            $body = $resp ? ($resp->json() ?? ['message' => $e->getMessage()]) : ['message' => $e->getMessage()];
            return response()->json($body, $status);
        } catch (\Throwable $e) {
            \Log::error('Update janji temu error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui janji temu. Silakan coba lagi',
                'debug' => config('app.debug') ? $e->getMessage() : null,
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Hapus (batalkan) janji temu berdasarkan ID.
     */
    public function deleteJanjiTemu(Request $request, $id): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'timestamp' => now()->toISOString(),
            ], 401);
        }

        try {
            $this->gateway->setToken($token);
            $result = $this->gateway->delete('janji/' . urlencode($id));

            if (!is_array($result)) {
                $result = ['success' => true, 'data' => $result];
            } elseif (!array_key_exists('success', $result)) {
                $result['success'] = true;
            }

            return response()->json($result, 200);
        } catch (RequestException $e) {
            $resp = $e->response;
            $status = $resp ? $resp->status() : 500;
            $body = $resp ? ($resp->json() ?? ['message' => $e->getMessage()]) : ['message' => $e->getMessage()];
            return response()->json($body, $status);
        } catch (\Throwable $e) {
            \Log::error('Delete janji temu error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan saat membatalkan janji temu',
                'debug' => config('app.debug') ? $e->getMessage() : null,
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }
}
