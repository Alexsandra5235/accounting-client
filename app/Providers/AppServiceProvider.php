<?php

namespace App\Providers;

use App\Interfaces\LogModelInterface;
use App\Repository\Api\ApiRepository;
use App\Repository\ClassifiersRepository;
use App\Repository\DiagnosisRepository;
use App\Repository\LogDischargeRepository;
use App\Repository\LogReceiptRepository;
use App\Repository\LogRejectRepository;
use App\Repository\LogRepository;
use App\Repository\PatientRepository;
use App\Services\Api\ApiService;
use App\Services\ClassifiersService;
use App\Services\DiagnosisService;
use App\Services\LogDischargeService;
use App\Services\LogReceiptService;
use App\Services\LogRejectService;
use App\Services\LogService;
use App\Services\PatientService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiRepository::class, function () {
            return new ApiRepository();
        });

        $this->app->singleton(ApiService::class, function () {
            return new ApiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
