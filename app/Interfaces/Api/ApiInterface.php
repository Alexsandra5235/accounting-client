<?php

namespace App\Interfaces\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;

interface ApiInterface
{
    public function getRequest(string $token, string $url): Response;
}
