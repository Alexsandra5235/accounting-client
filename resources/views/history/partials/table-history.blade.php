<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Просмотр истории') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('На данной странице представлена история по действиям в системе с подробным описанием проделанной работы.') }}
        </p>
    </header>

    <div class="timeline mt-6 space-y-6">
        <ul>
            @foreach($history as $item)
                <li>
                    <span>{{ \Carbon\Carbon::parse($item->created_at)->locale('ru')->translatedFormat('D, d M Y H:i') }}</span>
                    <div class="content">
                        <h3>{{ $item->action->getEnum()->message() }}</h3>
                        <p>
                            {{ $item->action->getEnum()->fullMessage($item->log_id, $item->user_id) }}
                        </p>
                        @if($item->log_id)
                            <p>
                                <x-link-primary-button href="{{ route('log.find', ['id' => $item->log_id]) }}" target="_blank">Перейти к записи о пациенте '{{ $item->log()['patient']['name'] }}'</x-link-primary-button>
                            </p>
                        @endif
                        @if($item->user_id)
                            <x-primary-button class="open-modal-btn"
                                    data-user-id="{{ $item->user_id }}"
                                    data-name="{{ $item->user->name }}"
                                    data-email="{{ $item->user->email }}"
                                    data-edit-url="{{ route('platform.systems.users.edit', $item->user_id) }}">
                                Информация о сотруднике '{{ $item->user->name }}'
                            </x-primary-button>
                        @endif
                        @if($item->diff)
                            <x-primary-button class="open-modal-btn"
                                              data-changes="{{ json_encode($item->diff, JSON_HEX_APOS | JSON_HEX_QUOT) }}">
                                Просмотр изменений
                            </x-primary-button>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Модальное окно одно на всю страницу -->
    <div id="modal" class="modal-overlay" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-content">
            <button class="modal-close" aria-label="Закрыть">&times;</button>
            <h3 id="modal-title" style="color: #1a88ff; margin-bottom: 10px"></h3>
            <p id="modal-body"></p>
        </div>
    </div>
</section>





