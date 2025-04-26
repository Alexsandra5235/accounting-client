<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Главная страница') }}
        </h2>
        <p class="font-normal text-gray-800 dark:text-gray-200 leading-tight">
            На данной странице отображается краткая информация о пациентах санатория.
        </p>
    </x-slot>

    @error('error_show')
    <div style="padding-top: 48px">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div style="padding: 24px 0 0 24px" class="flex items-center text-gray-900 dark:text-gray-100">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Ошибка просмотра записи') }}
                    </h2>
                </div>
                <div class="flex items-center p-6 text-gray-900 dark:text-gray-100">
                    <svg class="w-6 h-6 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9z" />
                    </svg>
                    {{ $message }}
                </div>
            </div>
        </div>
    </div>
    @enderror

    @error('error_edit')
    <div style="padding-top: 48px">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div style="padding: 24px 0 0 24px" class="flex items-center text-gray-900 dark:text-gray-100">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Ошибка редактирования записи') }}
                    </h2>
                </div>
                <div class="flex items-center p-6 text-gray-900 dark:text-gray-100">
                    <svg class="w-6 h-6 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9z" />
                    </svg>
                    {{ $message }}
                </div>
            </div>
        </div>
    </div>
    @enderror

    @error('error_delete')
    <div style="padding-top: 48px">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div style="padding: 24px 0 0 24px" class="flex items-center text-gray-900 dark:text-gray-100">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Ошибка удаления записи') }}
                    </h2>
                </div>
                <div class="flex items-center p-6 text-gray-900 dark:text-gray-100">
                    <svg class="w-6 h-6 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9z" />
                    </svg>
                    {{ $message }}
                </div>
            </div>
        </div>
    </div>
    @enderror

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
                                            <li class="border"><a href="{{ route('log.edit', ['id' => $log->id]) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Редактировать</a></li>
                                            <li class="border block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                                <form action="{{ route('log.destroy', ['id' => $log->id]) }}" method="post" onsubmit="return confirmDeletion()">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="submit" value="Удалить">
                                                </form>
                                            </li>
                                            <li class="border"><a href="{{ route('log.find', ['id' => $log->id]) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Просмотр</a></li>
                                        </ul>
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
