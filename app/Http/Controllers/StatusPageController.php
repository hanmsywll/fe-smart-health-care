<?php

namespace App\Http\Controllers;

use App\Services\ApiGateway;
use Illuminate\View\View;

class StatusPageController extends Controller
{
    public function index(ApiGateway $api): View
    {
        try {
            $data = $api->get('/status');
        } catch (\Throwable $e) {
            $data = null;
            $error = $e->getMessage();
        }

        return view('status', [
            'data' => $data,
            'error' => $error ?? null,
        ]);
    }
}