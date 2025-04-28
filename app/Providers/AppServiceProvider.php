<?php

namespace App\Providers;

use App\Repository\Api\ApiRepository;
use App\Services\Api\ApiService;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
