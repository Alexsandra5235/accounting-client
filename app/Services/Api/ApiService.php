<?php

namespace App\Services\Api;

use App\Repository\Api\ApiRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

class ApiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @throws ConnectionException
     */
    public function getLogs(string $token): Response
    {
        return app(ApiRepository::class)->getRequest($token, env('API_LOG_ALL_URL'));
    }
}
