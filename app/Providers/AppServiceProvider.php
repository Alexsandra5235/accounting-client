<?php

namespace App\Providers;

use app\Interfaces\LogInterface;
use App\Repository\LogDischargeRepository;
use App\Repository\LogReceiptRepository;
use App\Repository\LogRejectRepository;
use App\Services\LogDischargeService;
use App\Services\LogReceiptService;
use App\Services\LogRejectService;
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

        $this->app->singleton(LogReceiptService::class, function () {
            return new LogReceiptService();
        });

        $this->app->singleton(LogRejectService::class, function () {
            return new LogRejectService();
        });

        $this->app->singleton(LogDischargeService::class, function () {
            return new LogDischargeService();
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
