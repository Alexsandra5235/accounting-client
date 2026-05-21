<div class="pagination-container">
    <div class="pagination-info">
        <span>
            Показано {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }}
            из {{ $paginator->total() }} записей
        </span>

        @if($paginator->total() > $paginator->perPage())
            <span>
                Страница {{ $paginator->currentPage() }} из {{ $paginator->lastPage() }}
            </span>
        @endif
    </div>

    @if($paginator->hasPages())
        <div class="pagination-controls">
            {{-- Кнопка "Первая" --}}
            <button
                class="pagination-btn {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
                onclick="loadPage('{{ $tabName }}', 1)"
                {{ $paginator->onFirstPage() ? 'disabled' : '' }}
            >
                <i class="fas fa-angle-double-left"></i>
            </button>

            {{-- Кнопка "Назад" --}}
            <button
                class="pagination-btn {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
                onclick="loadPage('{{ $tabName }}', {{ $paginator->currentPage() - 1 }})"
                {{ $paginator->onFirstPage() ? 'disabled' : '' }}
            >
                <i class="fas fa-angle-left"></i>
            </button>

            {{-- Номера страниц --}}
            @php
                $start = max(1, $paginator->currentPage() - 2);
                $end = min($start + 4, $paginator->lastPage());
                $start = max(1, $end - 4);
            @endphp

            @if($start > 1)
                <button class="pagination-btn" onclick="loadPage('{{ $tabName }}', 1)">1</button>
                @if($start > 2)
                    <span class="pagination-ellipsis">...</span>
                @endif
            @endif

            @for($page = $start; $page <= $end; $page++)
                <button
                    class="pagination-btn {{ $page == $paginator->currentPage() ? 'active' : '' }}"
                    onclick="loadPage('{{ $tabName }}', {{ $page }})"
                >
                    {{ $page }}
                </button>
            @endfor

            @if($end < $paginator->lastPage())
                @if($end < $paginator->lastPage() - 1)
                    <span class="pagination-ellipsis">...</span>
                @endif
                <button class="pagination-btn" onclick="loadPage('{{ $tabName }}', {{ $paginator->lastPage() }})">
                    {{ $paginator->lastPage() }}
                </button>
            @endif

            {{-- Кнопка "Вперед" --}}
            <button
                class="pagination-btn {{ $paginator->hasMorePages() ? '' : 'disabled' }}"
                onclick="loadPage('{{ $tabName }}', {{ $paginator->currentPage() + 1 }})"
                {{ !$paginator->hasMorePages() ? 'disabled' : '' }}
            >
                <i class="fas fa-angle-right"></i>
            </button>

            {{-- Кнопка "Последняя" --}}
            <button
                class="pagination-btn {{ $paginator->hasMorePages() ? '' : 'disabled' }}"
                onclick="loadPage('{{ $tabName }}', {{ $paginator->lastPage() }})"
                {{ !$paginator->hasMorePages() ? 'disabled' : '' }}
            >
                <i class="fas fa-angle-double-right"></i>
            </button>
        </div>
    @endif
</div>
