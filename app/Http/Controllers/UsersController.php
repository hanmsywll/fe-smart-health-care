<?php

namespace App\Http\Controllers;

use App\Services\ApiGateway;
use Illuminate\View\View;
use Illuminate\Support\Arr;

class UsersController extends Controller
{
    public function index(ApiGateway $api): View
    {
        try {
            $data = $api->get('/users');
            $users = is_array($data) ? ($data['data'] ?? $data) : [];
        } catch (\Throwable $e) {
            $users = [];
            $error = $e->getMessage();
        }

        return view('users', [
            'users' => $users,
            'error' => $error ?? null,
        ]);
    }
}