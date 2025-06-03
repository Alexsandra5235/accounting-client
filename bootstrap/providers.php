<?php

return [
    App\Providers\AppServiceProvider::class,
    Orchid\Platform\Providers\PlatformServiceProvider::class,
    Orchid\Platform\Providers\RouteServiceProvider::class,
    App\Orchid\PlatformProvider::class,
//    App\Orchid\CustomPlatformProvider::class,
    Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    Telegram\Bot\Laravel\TelegramServiceProvider::class,
];
