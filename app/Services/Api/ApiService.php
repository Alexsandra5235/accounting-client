<?php

namespace App\Services\Api;

use App\Repository\Api\ApiRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use stdClass;

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
    public function getLogs(string $token): array
    {
        return json_decode(app(ApiRepository::class)->getRequest($token, env('API_LOG_ALL_URL'))->body());
    }

    /**
     * @throws ConnectionException
     */
    public function getLogById(string $token, string $id): Response
    {
        $url = env('API_LOG_URL') . "/{$id}";
        return app(ApiRepository::class)->getRequest($token,$url);
    }

    /**
     * @throws ConnectionException
     */
    public function updateLog(Request $request, string $token, string $id): Response
    {
        $url = env('API_LOG_URL') . "/{$id}";
        return app(ApiRepository::class)->putRequest($token, $url, $request);
    }

    /**
     * @throws ConnectionException
     */
    public function createLog(Request $request, string $token): Response
    {
        return app(ApiRepository::class)->postRequest($token, env('API_LOG_URL'), $request);
    }

    /**
     * @throws ConnectionException
     */
    public function deleteLog(string $token, string $id): Response
    {
        $url = env('API_LOG_URL') . "/{$id}";
        return app(ApiRepository::class)->deleteRequest($token, $url);
    }

    /**
     * @throws ConnectionException
     */
    public function getLogByName(string $token, Request $request): Response
    {
        return app(ApiRepository::class)->postRequest($token, env('API_LOG_SEARCH_URL'), $request);
    }
}
