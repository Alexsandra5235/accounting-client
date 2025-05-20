<?php

namespace App\Providers;

use App\Facades\ExcelStyler;
use App\Repository\Api\ApiRepository;
use App\Services\Api\ApiService;
use App\Services\Export\ExportToExcel;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
