<?php

namespace App\Repository\Api;

use App\Interfaces\Api\ApiInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
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

    /**
     * @param string $token
     * @param string $url
     * @param Request $request
     * @return Response
     * @throws ConnectionException
     */
    public function putRequest(string $token, string $url, Request $request): Response
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->asForm()->put($url, request()->all());
    }

    /**
     * @param string $token
     * @param string $url
     * @param Request|array $request
     * @return Response
     * @throws ConnectionException
     */
    public function postRequest(string $token, string $url, Request|array $request): Response
    {
        $data = is_array($request) ? $request : $request->all();
        \Log::warning('Request: ' . json_encode($data));
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->asForm()->post($url, $data);
    }

    /**
     * @param string $token
     * @param string $url
     * @return Response
     * @throws ConnectionException
     */
    public function deleteRequest(string $token, string $url): Response
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->asForm()->delete($url);
    }
}
