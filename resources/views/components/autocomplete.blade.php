@props(['name', 'placeholder' => '', 'url', 'value' => 'Диагноз заболевания', 'initial' => ''])

<div x-data="addressAutocomplete('{{ $url }}', '{{ $name }}', '{{ $initial }}')" class="w-full" @click.outside="suggestions = []">
    <x-input-label for="{{ $name }}" :value="__($value)" />
    <x-text-input
        id="{{ $name }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        type="text"
        class="mt-1 block w-full"
        x-model="query"
        @input.debounce.300ms="fetchSuggestions()"
    />
    <ul
        x-show="suggestions.length"
        x-cloak
        class="z-[2147483647] w-full bg-gray-900 border border-gray-700 rounded max-h-48 overflow-auto shadow-lg overflow-y-auto"
        style="display: none; max-height: 200px; "
        x-ref="dropdown"
    >
        <template x-for="(item, index) in suggestions" :key="index">
            <li
                :class="highlightedIndex === index ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                class="px-3 py-2 cursor-default"
                @mouseenter="highlightedIndex = index"
                @mouseleave="highlightedIndex = -1"
                @click="selectSuggestion(index)"
                x-text="`${item.value}`"
            ></li>
        </template>
    </ul>
    <x-input-error class="mt-2" :messages="$errors->get('{{ $name }}')" />
</div>
