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
        $this->app->singleton(LogReceiptRepository::class, function () {
            return new LogReceiptRepository();
        });

        $this->app->singleton(LogRejectRepository::class, function () {
            return new LogRejectRepository();
        });

        $this->app->singleton(LogDischargeRepository::class, function () {
            return new LogDischargeRepository();
        });

        $this->app->singleton(ClassifiersRepository::class, function () {
            return new ClassifiersRepository();
        });

        $this->app->singleton(DiagnosisRepository::class, function () {
            return new DiagnosisRepository();
        });

        $this->app->singleton(PatientRepository::class, function () {
            return new PatientRepository();
        });

        $this->app->singleton(LogRepository::class, function () {
            return new LogRepository();
        });

        $this->app->singleton(ApiRepository::class, function () {
            return new ApiRepository();
        });

        $this->app->singleton(LogReceiptService::class, function () {
            return new LogReceiptService();
        });

        $this->app->singleton(LogRejectService::class, function () {
            return new LogRejectService();
        });

        $this->app->singleton(LogDischargeService::class, function () {
            return new LogDischargeService();
        });

        $this->app->singleton(ClassifiersService::class, function () {
            return new ClassifiersService();
        });

        $this->app->singleton(DiagnosisService::class, function () {
            return new DiagnosisService();
        });

        $this->app->singleton(PatientService::class, function () {
            return new PatientService();
        });

        $this->app->singleton(LogService::class, function () {
            return new LogService();
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
