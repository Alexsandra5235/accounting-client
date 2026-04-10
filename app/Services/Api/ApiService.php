<?php

namespace App\Services\Api;

use App\Repository\Api\ApiRepository;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use stdClass;

class ApiService
{
    /**
     * @throws ConnectionException
     */
    public function getLogs(string $token): array
    {
        return json_decode(app(ApiRepository::class)->getRequest($token, env('API_LOG_ALL_URL'))->body());
    }

    /**
     * @throws ConnectionException
     */
    public function getLogById(string $token, string $id): Response
    {
        $url = env('API_LOG_URL') . "/{$id}";
        return app(ApiRepository::class)->getRequest($token,$url);
    }

    /**
     * @throws ConnectionException
     */
    public function updateLog(Request $request, string $token, string $id): Response
    {
        $url = env('API_LOG_URL') . "/{$id}";
        return app(ApiRepository::class)->putRequest($token, $url, $request);
    }

    /**
     * @throws ConnectionException
     */
    public function createLog(Request $request, string $token): Response
    {
        return app(ApiRepository::class)->postRequest($token, env('API_LOG_URL'), $request);
    }

    /**
     * @throws ConnectionException
     */
    public function deleteLog(string $token, string $id): Response
    {
        $url = env('API_LOG_URL') . "/{$id}";
        return app(ApiRepository::class)->deleteRequest($token, $url);
    }

    /**
     * @throws ConnectionException
     */
    public function getLogByName(string $token, Request $request): Response
    {
        return app(ApiRepository::class)->postRequest($token, env('API_LOG_SEARCH_URL'), $request);
    }

    /**
     * @throws Exception
     */
    public function getGrouping(array $request): Collection
    {
        try {
            $request = new Request($request);
            $group = app(ApiRepository::class)->postRequest(env('API_LOG_TOKEN'), env('API_LOG_GROUPING'), $request);
            if ($group->badRequest()){
                throw new Exception($group->getBody());
            }
            return collect(json_decode($group->getBody()->getContents(), true));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getCurrentPatient(): Collection
    {
        try {
            $currentPatient = app(ApiRepository::class)->getRequest(env('API_LOG_TOKEN'), env('API_LOG_CURRENT_PATIENT'));
            if ($currentPatient->badRequest()){
                throw new Exception($currentPatient->getBody());
            }
            return collect(json_decode($currentPatient->getBody()->getContents(), true));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getTodayReceipt(): Collection
    {
        try {
            $currentPatient = app(ApiRepository::class)->getRequest(env('API_LOG_TOKEN'), env('API_LOG_TODAY_RECEIPT'));
            if ($currentPatient->badRequest()){
                throw new Exception($currentPatient->getBody());
            }
            return collect(json_decode($currentPatient->getBody()->getContents(), true));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getTodayDischarge(): Collection
    {
        try {
            $currentPatient = app(ApiRepository::class)->getRequest(env('API_LOG_TOKEN'), env('API_LOG_TODAY_DISCHARGE'));
            if ($currentPatient->badRequest()){
                throw new Exception($currentPatient->getBody());
            }
            return collect(json_decode($currentPatient->getBody()->getContents(), true));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function findClassifiers(Request $request): array
    {
        try {
            $classifiers = app(ApiRepository::class)
                ->postRequest(config('api.log_token'), config('api.log_classifiers_url'), $request);
            if ($classifiers->badRequest() || $classifiers->getStatusCode() !== 200){
                throw new Exception($classifiers->getBody());
            }
            return json_decode($classifiers->getBody()->getContents(), true);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
