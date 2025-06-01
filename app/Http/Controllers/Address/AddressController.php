<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Services\Address\AddressService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Возвращает JSON-массив подсказок по адресу.
     *
     * Ожидает на входе JSON или form-data с полем "query".
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws ConnectionException
     */
    public function suggestAddress(Request $request): JsonResponse
    {
        // Читаем параметр "query" из тела запроса
        $query = app(AddressService::class)->getAddress($request);

        if (mb_strlen(trim($query)) < 2) {
            // Если строка слишком короткая — возвращаем пустой массив
            return response()->json([]);
        }

        // Отправляем запрос к DaData через сервис
        $response = app(AddressService::class)->suggest($query);

        // Если DaData вернула ошибку — возвращаем пустой массив
        if ($response->failed()) {
            return response()->json();
        }

        $result = app(AddressService::class)->parseSuggestions($response);

        return response()->json($result);
    }

    /**
     * @throws ConnectionException
     */
    public function suggestPlace(Request $request): JsonResponse
    {
        // Читаем параметр "query" из тела запроса
        $query = app(AddressService::class)->getPlace($request);

        if (mb_strlen(trim($query)) < 2) {
            // Если строка слишком короткая — возвращаем пустой массив
            return response()->json([]);
        }

        // Отправляем запрос к DaData через сервис
        $response = app(AddressService::class)->suggest($query);

        // Если DaData вернула ошибку — возвращаем пустой массив
        if ($response->failed()) {
            return response()->json();
        }

        $result = app(AddressService::class)->parseSuggestions($response);

        return response()->json($result);
    }
}
