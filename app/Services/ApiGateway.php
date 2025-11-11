<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiGateway
{
    protected string $baseUrl;
    protected ?string $token;
    protected int $timeout;

    public function __construct()
    {
        $config = config('services.api_gateway');

        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');

        if (empty($this->baseUrl)) {
            $this->baseUrl = 'https://smart-healthcare-system-production-6e7c.up.railway.app/api';
        }

        $this->token = session('api_gateway_token') ?? ($config['token'] ?? null);

        $this->timeout = (int) ($config['timeout'] ?? 15);
    }

    protected function client()
    {
        $headers = [];

        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return Http::withHeaders($headers)
            ->accept('application/json')
            ->timeout($this->timeout);
    }

    public function get(string $path, array $query = [])
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $this->client()
            ->get($url, $query)
            ->throw()
            ->json();
    }

    public function post(string $path, array $data = [])
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $this->client()
            ->asJson()
            ->post($url, $data)
            ->throw()
            ->json();
    }

    public function put(string $path, array $data = [])
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $this->client()
            ->asJson()
            ->put($url, $data)
            ->throw()
            ->json();
    }

    public function delete(string $path)
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $this->client()
            ->delete($url)
            ->throw()
            ->json();
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    
}
