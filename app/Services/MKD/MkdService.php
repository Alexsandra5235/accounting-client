<?php

namespace App\Services\MKD;

use App\Services\Api\ApiService;
use Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class MkdService
{
    public function getRequestState(Request $request): string
    {
        return $request->input('state_code');
    }
    public function getRequestWound(Request $request): string
    {
        return $request->input('wound_code');
    }

    /**
     * @throws \Exception
     */
    public function suggest(Request $request): array
    {
        return app(ApiService::class)->findClassifiers($request);
    }

    public function getResult(array $suggestions): JsonResponse
    {
        $result = array_map(function($item) {
            return [
                'code' => $item['code'] ?? '',
                'value' => $item['value'] ?? '',
            ];
        }, $suggestions);

        return response()->json($result);
    }
}
