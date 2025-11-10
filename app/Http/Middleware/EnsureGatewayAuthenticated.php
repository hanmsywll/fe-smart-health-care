<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ApiGateway;

class EnsureGatewayAuthenticated
{
    protected ApiGateway $gateway;

    public function __construct(ApiGateway $gateway)
    {
        $this->gateway = $gateway;
    }
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('api_gateway_token')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login untuk mengakses halaman ini.'
                ], 401);
            }

            $redirectUrl = '/login?redirect=' . urlencode($request->fullUrl());
            return redirect($redirectUrl);
        }

        if (!session()->has('user')) {
            try {
                $me = $this->gateway->get('auth/me');
                $user = data_get($me, 'user') ?? data_get($me, 'data.user') ?? $me;
                if ($user && is_array($user)) {
                    session(['user' => $user]);
                }
            } catch (\Throwable $e) {
            }
        }

        return $next($request);
    }
}