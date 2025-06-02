<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Просмотр истории') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('На данной странице представлена история формирования отчетов. При необходимости можно скачать уже сформированный отчет за указанную дату без необходимости повторного формирования.') }}
        </p>
    </header>

    <div style="max-height: calc(100vh - 300px); overflow-y: auto;" class="timeline-wrapper">
        <div class="timeline mt-6 space-y-6">
            <ul>
                @foreach($reports as $item)
                    <li>
                        <span>{{ \Carbon\Carbon::parse($item->created_at)->locale('ru')->translatedFormat('D, d M Y H:i') }}</span>
                        <div class="content">
                            <h3>{{ $item->filename }}</h3>
                            <p>
                                Для скачивания отчета нажмите на кнопку ниже
                            </p>
                            <p>
                                <x-link-primary-button href="{{ route('reports.download', ['id' => $item->id]) }}">Скачать</x-link-primary-button>
                            </p>

                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>





