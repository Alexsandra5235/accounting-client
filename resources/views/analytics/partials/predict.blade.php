<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Прогноз поступлений и выписок на ближайшие 12 месяцев') }}
        </h2>

        @if ($message)
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $message }}
            </p>
        @endif
    </header>

    <div class="overflow-x-auto bg-white rounded shadow py-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Месяц
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Прогноз поступлений
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Прогноз выписок
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($predictions as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($item['month'])->format('M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                        {{ $item['admissions'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        {{ $item['discharges'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
