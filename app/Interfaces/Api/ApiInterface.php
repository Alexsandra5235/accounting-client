<?php

namespace App\Interfaces\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ApiInterface
{
    public function getRequest(string $token, string $url): Response;
    public function putRequest(string $token, string $url, Request $request): Response;
    public function postRequest(string $token, string $url, Request $request): Response;
}
