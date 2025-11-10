<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class ApiGateway
{
    protected string $baseUrl;
    protected ?string $token;
    protected int $timeout;

    public function __construct()
    {
        $config = config('services.api_gateway');
        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');
        if ($this->baseUrl === '') {
            // Fallback ke base URL Railway jika ENV tidak di-set
            $this->baseUrl = 'https://smart-healthcare-system-production-6e7c.up.railway.app/api';
        }
        $this->token = $config['token'] ?? null;
        $this->timeout = (int) ($config['timeout'] ?? 15);
    }

    protected function client()
    {
        $headers = [];
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return Http::withHeaders($headers)->timeout($this->timeout);
    }

    public function get(string $path, array $query = [])
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $this->client()->get($url, $query)->throw()->json();
    }

    public function post(string $path, array $data = [])
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $this->client()->post($url, $data)->throw()->json();
    }
}