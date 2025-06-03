<?php

namespace App\Orchid\Layouts\Examples;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class TabMenuExample extends TabMenu
{
    /**
     * Get the menu elements to be displayed.
     *
     * @return Menu[]
     */
    protected function navigations(): iterable
    {
        return [
            Menu::make('Обзор макетов')
                ->route('platform.example.layouts'),

            Menu::make('Начать')
                ->route('platform.main'),

            Menu::make('Документация')
                ->url('https://orchid.software/en/docs'),

            Menu::make('Пример экрана')
                ->route('platform.example')
                ->badge(fn () => 6),
        ];
    }
}
