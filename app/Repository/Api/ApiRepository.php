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
        ])->asForm()->put($url, [
            'date_receipt' => $request->input('date_receipt'),
            'time_receipt' => $request->input('time_receipt'),
            'name' => $request->input('name'),
            'birth_day' => $request->input('birth_day'),
            'gender' => $request->input('gender'),
            'medical_card' => $request->input('medical_card'),
            'passport' => $request->input('passport'),
            'nationality' => $request->input('nationality'),
            'address' => $request->input('address'),
            'snils' => $request->input('snils'),
            'polis' => $request->input('polis'),
            'phone_agent' => $request->input('phone_agent'),
            'delivered' => $request->input('delivered'),
            'state_code' => $request->input('state_code'),
            'wound_code' => $request->input('wound_code'),
            'fact_alcohol' => $request->input('fact_alcohol'),
            'datetime_alcohol' => $request->input('datetime_alcohol'),
            'result_research' => $request->input('result_research'),
            'section_medical' => $request->input('section_medical'),
            'outcome' => $request->input('outcome'),
            'datetime_discharge' => $request->input('datetime_discharge'),
            'section_transferred' => $request->input('section_transferred'),
            'datetime_inform' => $request->input('datetime_inform'),
            'reason_refusal' => $request->input('reason_refusal'),
            'name_medical_worker' => $request->input('name_medical_worker'),
            'add_info' => $request->input('add_info'),
        ]);
    }
}
