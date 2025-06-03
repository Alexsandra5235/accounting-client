<?php

namespace App\Orchid\Layouts\Examples;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class ExampleElements extends TabMenu
{
    /**
     * Get the menu elements to be displayed.
     *
     * @return Menu[]
     */
    protected function navigations(): iterable
    {
        return [
            Menu::make('Основные элементы')
                ->route('platform.example.fields'),

            Menu::make('Продвинутые элементы')
                ->route('platform.example.advanced'),

            Menu::make('Текстовые редакторы')
                ->route('platform.example.editors'),

            Menu::make('Выполнение действий')
                ->route('platform.example.actions'),
        ];
    }
}
