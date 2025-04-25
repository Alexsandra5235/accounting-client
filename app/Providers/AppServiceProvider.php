<?php

namespace App\Providers;

use app\Interfaces\LogInterface;
use App\Repository\LogReceiptRepository;
use App\Repository\LogRejectRepository;
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

        $this->app->singleton(LogReceiptService::class, function () {
            return new LogReceiptService();
        });

        $this->app->singleton(LogRejectService::class, function () {
            return new LogRejectService();
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
