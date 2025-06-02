<?php

namespace App\Providers;

use App\Facades\ExcelStyler;
use App\Repository\Api\ApiRepository;
use App\Repository\History\HistoryRepository;
use App\Services\Address\AddressService;
use App\Services\Api\ApiService;
use App\Services\Export\GenerateExcelService;
use App\Services\History\HistoryService;
use App\Services\LogService;
use App\Services\MKD\MkdService;
use App\Services\TelegramService;
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

        $this->app->singleton(TelegramService::class, function () {
            return new TelegramService();
        });

        $this->app->singleton(ExcelStyler::class, function () {
            return new ExcelStyler();
        });

        $this->app->singleton(GenerateExcelService::class, function () {
            return new GenerateExcelService();
        });

        $this->app->singleton(LogService::class, function () {
            return new LogService();
        });

        $this->app->singleton(MkdService::class, function () {
            return new MkdService();
        });

        $this->app->singleton(AddressService::class, function () {
            return new AddressService();
        });

        $this->app->singleton(HistoryRepository::class, function () {
            return new HistoryRepository();
        });

        $this->app->singleton(HistoryService::class, function () {
            return new HistoryService();
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
