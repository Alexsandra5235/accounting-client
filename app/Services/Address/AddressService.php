<?php

namespace App\Services\Address;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AddressService
{
    /**
     * Отправляет POST-запрос к DaData, получает подсказки по адресу.
     *
     * @param  string  $query
     * @return Response
     * @throws ConnectionException
     */
    public function suggest(string $query): Response
    {
        $apiKey = env('DADATA_API_KEY');
        $url = env('DADATA_SUGGEST_URL');

        // Если ключ или URL не указаны — выбрасываем исключение
        if (empty($apiKey) || empty($url)) {
            throw new RuntimeException('Dadata API key or URL is not configured.');
        }

        // Выполняем POST-запрос
        return Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Token ' . $apiKey,
        ])->post($url, [
            'query' => $query,
        ]);
    }

    /**
     * Извлекает из ответа только массив подсказок в нужном формате.
     *
     * @param  Response  $response
     * @return array<int, array<string, string>>
     */
    public function parseSuggestions(Response $response): array
    {
        $all = $response->json('suggestions', []);

        if (! is_array($all)) {
            return [];
        }

        return array_map(function($item) {
            return [
                'value' => data_get($item, 'value', ''),
            ];
        }, $all);
    }

    public function getAddress(Request $request): string
    {
        return $request->input('address');
    }

    public function getPlace(Request $request): string
    {
        return $request->input('register_place');
    }
}
