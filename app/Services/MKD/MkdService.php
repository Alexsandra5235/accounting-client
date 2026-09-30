<?php

namespace App\Services\MKD;

use App\Services\Api\ApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


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

    /**
     * @throws \Exception
     */
    public function search(string $query): array
    {
        $request = new \Illuminate\Http\Request([
            'query' => $query,
        ]);

        return $this->suggest($request);
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
