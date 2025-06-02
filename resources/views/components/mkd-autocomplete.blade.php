@props(['name', 'placeholder' => '', 'url', 'value' => 'Диагноз заболевания', 'initial' => '', 'hidden' => ''])

@php
    $nameCode = $name . '_code';
    $nameValue = $name . '_value';
@endphp

<div x-data="mkbAutocomplete('{{ $url }}', '{{ $nameCode }}', '{{ $nameValue }}', '{{ $initial }}', '{{ $hidden }}')" class="w-full" @click.outside="suggestions = []">
    <x-input-label for="{{ $nameCode }}" :value="__($value)" />
    <x-text-input
        id="{{ $nameCode }}"
        name="{{ $nameCode }}"
        placeholder="{{ $placeholder }}"
        type="text"
        class="mt-1 block w-full"
        x-model="query"
        @input.debounce.300ms="fetchSuggestions()"
    />
    <ul
        x-show="suggestions.length"
        x-cloak
        class="z-[2147483647] w-full bg-gray-900 border border-gray-700 rounded max-h-48 overflow-auto shadow-lg"
        style="display: none;"
        x-ref="dropdown"
    >
        <template x-for="(item, index) in suggestions" :key="index">
            <li
                :class="highlightedIndex === index ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                class="px-3 py-2 cursor-default"
                @mouseenter="highlightedIndex = index"
                @mouseleave="highlightedIndex = -1"
                @click="selectSuggestion(index)"
                x-text="`${item.code} — ${item.value}`"
            ></li>
        </template>
    </ul>
    <input type="text" id="{{ $nameValue }}" name="{{ $nameValue }}" hidden="hidden" value="{{ $hidden }}">
    <x-input-error class="mt-2" :messages="$errors->get('{{ $nameCode }}')" />
</div>
