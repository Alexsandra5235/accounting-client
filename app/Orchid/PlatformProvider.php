<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
//            Menu::make('Графики')
//                ->icon('bs.bar-chart')
//                ->route('platform.patient.flow'),
//
//            Menu::make('Предсказания')
//                ->icon('bs.columns-gap')
//                ->route('platform.predict'),

            Menu::make(__('Пользователи'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Средства контроля доступа')),

            Menu::make(__('Роли'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make('Документация')
                ->title('Документы')
                ->icon('bs.box-arrow-up-right')
                ->url('https://orchid.software/en/docs')
                ->target('_blank'),

            Menu::make('Журнал изменений')
                ->icon('bs.box-arrow-up-right')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK),

            Menu::make(__('Вернуться на сайт'))
                ->icon('bs.people')
                ->route('platform.logout')
                ->permission('platform.systems.users')
                ->title(__('Навигация')),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('Доступ в систему'))
                ->addPermission('platform.index', __('Доступ в админку')),

            ItemPermission::group(__('Доступ к средствам контроля доступа'))
                ->addPermission('platform.systems.roles', __('Роли'))
                ->addPermission('platform.systems.users', __('Пользователи')),

            ItemPermission::group(__('Доступ к данным'))
                ->addPermission('access.edit', __('Редактирование'))
                ->addPermission('access.delete', __('Удаление'))
                ->addPermission('access.add', __('Добавление')),

            ItemPermission::group(__('Отчеты'))
                ->addPermission('report.create', __('Формирование отчетов'))
                ->addPermission('report.history', __('Просмотр истории формирования отчетов')),

            ItemPermission::group(__('Данные санатория'))
                ->addPermission('statistic', __('Просмотр статистики санатория'))
                ->addPermission('history', __('Просмотр истории взаимодействия с системой')),

        ];
    }
}
