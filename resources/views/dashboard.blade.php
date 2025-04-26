<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Главная страница') }}
        </h2>
        <p class="font-normal text-gray-800 dark:text-gray-200 leading-tight">
            На данной странице отображается краткая информация о пациентах санатория.
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table-auto border-collapse border border-gray-300 w-full">
                        <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Дата приема</th>
                            <th class="border border-gray-300 px-4 py-2">Время приема</th>
                            <th class="border border-gray-300 px-4 py-2">ФИО</th>
                            <th class="border border-gray-300 px-4 py-2">Дата рождения</th>
                            <th class="border border-gray-300 px-4 py-2">Медицинская карта</th>
                            <th class="border border-gray-300 px-4 py-2">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($log->log_receipt->date_receipt)->locale('ru')->translatedFormat('D, d M Y') }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $log->log_receipt->time_receipt }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $log->patient->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($log->patient->birth_day)->locale('ru')->translatedFormat('d M Y') }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $log->patient->medical_card }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <button onclick="toggleDropdown(this)" class="text-gray-600 focus:outline-none">⋮</button>
                                    <div class="dropdown-menu absolute right-0 hidden bg-white border border-gray-300 mt-1">
                                        <ul class="list-none p-2">
                                            <li class="border-b"><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Редактировать</a></li>
                                            <li class="border-b"><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Удалить</a></li>
                                            <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Просмотр</a></li>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
