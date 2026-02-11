@props(['name', 'placeholder' => '', 'url', 'value' => 'Диагноз заболевания', 'initial' => '', 'label' => '', 'icon' => null])

<div x-data="addressAutocomplete('{{ $url }}', '{{ $name }}', '{{ $initial }}')" class="w-full" @click.outside="suggestions = []">
    @if($label || $value)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            @if($icon)
                <i class="fas fa-{{ $icon }} mr-1 text-blue-600"></i>
            @endif
            {{ $label ?: __($value) }}
        </label>
    @endif

    <div class="relative">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            x-model="query"
            @input.debounce.300ms="fetchSuggestions()"
            @keydown.arrow-down="highlightNext()"
            @keydown.arrow-up="highlightPrev()"
            @keydown.enter.prevent="selectHighlighted()"
            value="{{ old($name) }}"
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
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-2 text-sm"></i>
                        <span x-text="item.value" class="text-sm"></span>
                    </div>
                    @if(isset($showCode) && $showCode)
                        <div x-show="item.code" class="text-xs text-gray-500 mt-1 ml-6" x-text="'Код: ' + item.code"></div>
                    @endif
                </div>
            </template>
        </div>
    </div>

    @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
    function addressAutocomplete(fetchUrl, nameInput, initial) {
        return {
            query: initial || '',
            suggestions: [],
            highlightedIndex: -1,
            isLoading: false,

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
                    .catch(() => {
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
                    const highlightedElement = this.$refs.dropdown.children[this.highlightedIndex];
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
                this.query = this.suggestions[index].value;
                this.suggestions = [];

                // Если нужно сохранить код в скрытом поле
                if (this.suggestions[index].code) {
                    const hiddenInput = document.getElementById(nameInput + '_code');
                    if (hiddenInput) {
                        hiddenInput.value = this.suggestions[index].code;
                    }
                }
            }
        };
    }
</script>

<style>
    /* Стили для скроллбара выпадающего списка */
    [x-ref="dropdown"]::-webkit-scrollbar {
        width: 6px;
    }

    [x-ref="dropdown"]::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    [x-ref="dropdown"]::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    [x-ref="dropdown"]::-webkit-scrollbar-thumb:hover {
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

    /* Для компонента MKD автодополнения (если нужен другой вид) */
    .mkd-suggestion .code {
        font-family: monospace;
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-left: 8px;
    }
</style>
