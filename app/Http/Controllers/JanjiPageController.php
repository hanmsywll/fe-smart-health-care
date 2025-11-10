<?php

namespace App\Http\Controllers;

use App\Services\ApiGateway;
use Illuminate\View\View;

class JanjiPageController extends Controller
{
    public function index(ApiGateway $api): View
    {
        try {
            $data = $api->get('/janji/ketersediaan-all');
            $items = is_array($data) ? ($data['data'] ?? $data) : [];
        } catch (\Throwable $e) {
            $items = [];
            $error = $e->getMessage();
        }

        return view('ketersediaan', [
            'items' => $items,
            'raw' => $data ?? null,
            'error' => $error ?? null,
        ]);
    }
}