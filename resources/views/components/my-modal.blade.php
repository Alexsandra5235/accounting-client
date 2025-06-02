@props(['name', 'show' => false, 'maxWidth' => '2xl'])

@php
    $maxWidthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div
    x-data="{ show: @js($show) }"
    x-on:open-modal.window="$event.detail === '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail === '{{ $name }}' ? show = false : null"
    x-init="$watch('show', value => document.body.classList.toggle('overflow-hidden', value))"
    x-cloak
>
    <div
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 z-9999 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div
            x-show="show"
            x-transition
            @click.outside="show = false"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-xl w-full mx-4 {{ $maxWidthClass }}"
        >
            {{ $slot }}
        </div>
    </div>
</div>
