<?php

namespace App\Http\Controllers;

use App\Services\ApiGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegister()
    {
        $redirectUrl = request()->query('redirect', '/login');

        return view('auth.register', ['redirectUrl' => $redirectUrl]);
    }

    public function showLogin()
    {
        $redirectUrl = request()->query('redirect', '/dashboard');

        return view('auth.login', ['redirectUrl' => $redirectUrl]);
    }

    public function login(Request $request, ApiGateway $gateway)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $payload = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        try {
            $result = $gateway->post('auth/login', $payload);

            $token = data_get($result, 'token')
                ?? data_get($result, 'access_token')
                ?? data_get($result, 'data.token');

            if (!$token) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal login: token tidak ditemukan'
                    ], 422);
                }
                return back()->withErrors(['email' => 'Gagal login: token tidak ditemukan'])->withInput();
            }

            session(['api_gateway_token' => $token]);
            config(['services.api_gateway.token' => $token]);

            $user = data_get($result, 'user') ?? data_get($result, 'data.user');
            if ($user) {
                session(['user' => $user]);
            } else {
                try {
                    $me = $gateway->get('auth/me');
                    $user = data_get($me, 'user') ?? data_get($me, 'data.user') ?? $me;
                    if ($user && is_array($user)) {
                        session(['user' => $user]);
                    }
                } catch (\Throwable $e) {
                }
            }

            $redirect = $request->input('redirect', '/dashboard');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => $user,
                    'redirect' => $redirect,
                ]);
            }
            return redirect($redirect)->with('status', 'Login berhasil');
        } catch (\Throwable $e) {
            Log::error('Login error: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal login: ' . $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['email' => 'Gagal login: ' . ($e->getMessage())])->withInput();
        }
    }

    public function logout(Request $request)
    {
        session()->forget(['api_gateway_token', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirect = $request->input('redirect', '/');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Anda telah logout.',
                'redirect' => $redirect,
            ]);
        }

        return redirect($redirect)->with('status', 'Anda telah berhasil logout.');
    }

    /**
     * Sinkronkan token dari client ke session server-side.
     */
    public function syncToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = $request->input('token');
        session(['api_gateway_token' => $token]);
        config(['services.api_gateway.token' => $token]);

        if ($user = $request->input('user')) {
            session(['user' => $user]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Proxy registrasi pasien ke API Gateway
     */
    public function registerPasien(Request $request, ApiGateway $gateway)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'nama_lengkap' => 'required|string',
            'no_telepon' => 'nullable|string',
        ]);

        $payload = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'nama_lengkap' => $request->input('nama_lengkap'),
            'no_telepon' => $request->input('no_telepon'),
        ];

        try {
            $result = $gateway->post('auth/register/pasien', $payload);
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => data_get($result, 'message') ?? 'Registrasi pasien berhasil',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Register pasien error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy registrasi dokter ke API Gateway
     */
    public function registerDokter(Request $request, ApiGateway $gateway)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'nama_lengkap' => 'required|string',
            'no_telepon' => 'required|string',
            'spesialisasi' => 'nullable|string',
            'no_lisensi' => 'nullable|string',
            'shift' => 'nullable|string',
            'biaya_konsultasi' => 'required|numeric|min:0',
        ]);

        $payload = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'nama_lengkap' => $request->input('nama_lengkap'),
            'no_telepon' => $request->input('no_telepon'),
            'spesialisasi' => $request->input('spesialisasi'),
            'no_lisensi' => $request->input('no_lisensi'),
            'shift' => $request->input('shift'),
            'biaya_konsultasi' => $request->input('biaya_konsultasi'),
        ];

        try {
            $result = $gateway->post('auth/register/dokter', $payload);
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => data_get($result, 'message') ?? 'Registrasi dokter berhasil',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Register dokter error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar: ' . $e->getMessage(),
            ], 500);
        }
    }
}
