<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ApiHelper
{

    public static function get(string $url, array $params = [])
    {
        return Http::withToken(session('jwt_token'))
        ->get(config('app.url') . '/api/v1/' . $url, $params);
    }

    public static function post(string $url, array $data = [])
    {
        return Http::withToken(session('jwt_token'))
        ->post(config('app.url') . '/api/v1/' . $url, $data);
    }

    public static function put(string $url, array $data = [])
    {
        return Http::withToken(session('jwt_token'))
        ->put(config('app.url') . '/api/v1/' . $url, $data);
    }

    public static function patch(string $url, array $data = [])
    {
        return Http::withToken(session('jwt_token'))
        ->patch(config('app.url') . '/api/v1/' . $url, $data);
    }

    public static function delete(string $url)
    {
        return Http::withToken(session('jwt_token'))
        ->delete(config('app.url') . '/api/v1/' . $url);
    }
}