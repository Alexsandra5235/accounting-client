<?php

namespace App\Repository\Api;

use App\Interfaces\Api\ApiInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Http;

class ApiRepository implements ApiInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $token
     * @param string $url
     * @return Response
     * @throws ConnectionException
     */
    public function getRequest(string $token, string $url): Response
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->get($url);
    }
}
