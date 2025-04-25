<?php

namespace App\Providers;

use app\Interfaces\LogInterface;
use App\Repository\LogReceiptRepository;
use App\Services\LogReceiptService;
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

        $this->app->singleton(LogReceiptService::class, function () {
            return new LogReceiptService();
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
