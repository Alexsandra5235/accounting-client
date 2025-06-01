<?php

namespace App\Services\MKD;

use Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
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
     * @throws ConnectionException
     */
    public function suggest(string $query): Response
    {
        return Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => env('API_TOKEN_GETGEO'),
            'Content-Type' => 'application/json',
        ])->post(env('API_URL_GETGEO'), [
            'query' => $query,
            'count' => 5,
        ]);
    }

    public function getResult(Response $response): JsonResponse
    {
        $suggestionsAssoc = $response->json('suggestions', []);

        // Приводим к индексированному массиву
        $suggestions = array_values($suggestionsAssoc);

        $result = array_map(function($item) {
            return [
                'code' => $item['data']['code'] ?? '',
                'value' => $item['value'] ?? '',
            ];
        }, $suggestions);

        return response()->json($result);
    }
}
