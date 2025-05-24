<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Формирование сводной ведомости') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Введите начальную дату для формирования сводной ведомости. После нажатия на кнопку, система сформирует ваш отчет за выбранный период.") }}
        </p>
    </header>

    <form method="post" action="{{ route('excel.download.summary') }}" class="mt-6 space-y-6">
        @csrf
        @method('post')

        <div>
            <x-input-label for="date1" :value="__('Дата начала')" />
            <x-text-input id="date1" name="date1" type="date" class="mt-1 block w-full" :value="old('date1')" />
            <x-input-error class="mt-2" :messages="$errors->get('date1')" />
        </div>

        <div>
            <x-input-label for="date2" :value="__('Дата конца')" />
            <x-text-input id="date2" name="date2" type="date" class="mt-1 block w-full" :value="old('date2')" />
            <x-input-error class="mt-2" :messages="$errors->get('date2')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Сформировать отчет') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
