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
     * @throws ConnectionException
     */
    public function suggestState(Request $request): JsonResponse
    {
        $query = app(MkdService::class)->getRequestState($request);
        if (!$query) {
            return response()->json();
        }

        $response = app(MkdService::class)->suggest($query);

        if ($response->failed()) {
            return response()->json();
        }

        return app(MkdService::class)->getResult($response);
    }

    /**
     * @throws ConnectionException
     */
    public function suggestWound(Request $request): JsonResponse
    {
        $query = app(MkdService::class)->getRequestWound($request);
        if (!$query) {
            return response()->json();
        }

        $response = app(MkdService::class)->suggest($query);

        if ($response->failed()) {
            return response()->json();
        }

        return app(MkdService::class)->getResult($response);
    }


}
