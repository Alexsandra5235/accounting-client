@props(['name', 'placeholder' => '', 'url', 'value' => 'Диагноз заболевания', 'initial' => '', 'hidden' => '', 'label' => '', 'icon' => null])

@php
    $nameCode = $name . '_code';
    $nameValue = $name . '_value';
@endphp

<div x-data="mkbAutocomplete('{{ $url }}', '{{ $nameCode }}', '{{ $nameValue }}', '{{ $initial }}', '{{ $hidden }}')" class="w-full" @click.outside="suggestions = []">
    @if($label || $value)
        <label for="{{ $nameCode }}" class="block text-sm font-medium text-gray-700 mb-1">
            @if($icon)
                <i class="fas fa-{{ $icon }} mr-1 text-blue-600"></i>
            @endif
            {{ $label ?: __($value) }}
        </label>
    @endif

    <div class="relative">
        <input
            id="{{ $nameCode }}"
            name="{{ $nameCode }}"
            placeholder="{{ $placeholder }}"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            x-model="query"
            @input.debounce.300ms="fetchSuggestions()"
            @keydown.arrow-down="highlightNext()"
            @keydown.arrow-up="highlightPrev()"
            @keydown.enter.prevent="selectHighlighted()"
            value="{{ old($nameCode) }}"
            autocomplete="off"
        />

        <!-- Индикатор загрузки -->
        <div x-show="isLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
        </div>

        <!-- Выпадающий список -->
        <div
            x-show="suggestions.length > 0"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-64 overflow-y-auto"
            style="display: none;"
            x-ref="dropdown"
        >
            <div class="py-1">
                <template x-for="(item, index) in suggestions" :key="index">
                    <div
                        :class="{
                            'bg-blue-50 text-blue-700 border-l-4 border-blue-500': highlightedIndex === index,
                            'text-gray-700 hover:bg-gray-50': highlightedIndex !== index
                        }"
                        class="px-4 py-3 cursor-pointer transition-colors border-l-4 border-transparent"
                        @mouseenter="highlightedIndex = index"
                        @mouseleave="highlightedIndex = -1"
                        @click="selectSuggestion(index)"
                    >
                        <div class="flex items-start space-x-2">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-6 flex items-center justify-center bg-blue-100 text-blue-700 rounded text-xs font-mono font-bold">
                                    <span x-text="item.code"></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate" x-text="item.value"></div>
                                <div x-show="item.description" class="text-xs text-gray-500 mt-1 truncate" x-text="item.description"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Скрытое поле для значения -->
    <input type="hidden" id="{{ $nameValue }}" name="{{ $nameValue }}" value="{{ $hidden ?: old($nameValue) }}">

    @error($nameCode)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error($nameValue)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
    function mkbAutocomplete(fetchUrl, nameInput, nameValue, initial, hidden) {
        return {
            query: initial || '',
            suggestions: [],
            highlightedIndex: -1,
            isLoading: false,

            init() {
                // Если есть hidden значение, установить его
                if (hidden) {
                    const hiddenInput = document.getElementById(nameValue);
                    if (hiddenInput) {
                        hiddenInput.value = hidden;
                    }
                }
            },

            fetchSuggestions() {
                if (this.query.length < 2) {
                    this.suggestions = [];
                    return;
                }

                this.isLoading = true;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(fetchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        [nameInput]: this.query
                    }),
                })
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data;
                        this.highlightedIndex = -1;
                    })
                    .catch((error) => {
                        console.error('Ошибка загрузки данных:', error);
                        this.suggestions = [];
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },

            highlightNext() {
                if (this.highlightedIndex < this.suggestions.length - 1) {
                    this.highlightedIndex++;
                    this.scrollToHighlighted();
                }
            },

            highlightPrev() {
                if (this.highlightedIndex > 0) {
                    this.highlightedIndex--;
                    this.scrollToHighlighted();
                }
            },

            scrollToHighlighted() {
                if (this.highlightedIndex >= 0 && this.$refs.dropdown) {
                    const highlightedElement = this.$refs.dropdown.children[0].children[this.highlightedIndex];
                    if (highlightedElement) {
                        highlightedElement.scrollIntoView({ block: 'nearest' });
                    }
                }
            },

            selectHighlighted() {
                if (this.highlightedIndex >= 0) {
                    this.selectSuggestion(this.highlightedIndex);
                }
            },

            selectSuggestion(index) {
                const selected = this.suggestions[index];
                this.query = `${selected.code} — ${selected.value}`;

                // Сохраняем код в скрытое поле
                const hiddenInput = document.getElementById(nameValue);
                if (hiddenInput) {
                    hiddenInput.value = selected.value;
                }

                this.suggestions = [];
            }
        };
    }
</script>

<style>
    /* Стили для скроллбара */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Анимация спиннера */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Скрытие для x-cloak */
    [x-cloak] {
        display: none !important;
    }
</style>
