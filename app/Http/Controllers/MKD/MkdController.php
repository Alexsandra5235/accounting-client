<?php

namespace App\Http\Controllers\MKD;

use App\Http\Controllers\Controller;
use App\Services\MKD\MkdService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MkdController extends Controller
{
    /**
     * @throws \Exception
     */
    public function suggestState(Request $request): JsonResponse
    {
        $response = app(MkdService::class)->suggest($request);

        return app(MkdService::class)->getResult($response);
    }


    /**
     * @throws \Exception
     */
    public function suggestWound(Request $request): JsonResponse
    {
        $response = app(MkdService::class)->suggest($request);

        return app(MkdService::class)->getResult($response);
    }


}
