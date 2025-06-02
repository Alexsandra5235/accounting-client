{{-- resources/views/components/three-dots-select.blade.php --}}
@props([
    'editRoute',        // URL для редактирования (строка)
    'viewRoute',        // URL для просмотра (строка)
    'recordId',         // ID записи (например, $log->id)
    'recordName',       // название/имя записи (для confirm)
])

{{--
  Стили у <select> такие, чтобы он был маленьким (w-6 h-6),
  всегда показывал «⋮» (в первой опции),
  и при выборе пункта сразу сбрасывался обратно на «⋮».
--}}
<select
    x-data
    x-init="
      /*
        Если браузер не Alpine-ready, всё равно отработает onchange-скрипт ниже,
        но Alpine здесь лишь для того, чтобы не сломалось, если `@`-директивы попали бы в Blade.
      "
    onchange="(function(sel){
        const val = sel.value;
        if (!val) {
            sel.selectedIndex = 0;
            return;
        }
        // Редирект на страницу редактирования
        if (val === 'edit') {
            window.location.href = '{{ $editRoute }}';
        }
        // Редирект на страницу просмотра
        if (val === 'view') {
            window.location.href = '{{ $viewRoute }}';
        }
        // Удаление: показываем confirm, если подтвердили — сабмитим скрытую форму
        if (val === 'delete') {
            if (confirm('Удалить запись «{{ addslashes($recordName) }}»?')) {
                document.getElementById('delete-form-{{ $recordId }}').submit();
            }
        }
        // Сбрасываем обратно на «⋮»
        sel.selectedIndex = 0;
    })(this)"
    {{-- Небольшие стили по примеру вашего компонента --}}
    {{ $attributes->merge([
        'class' => '
            border-gray-300
            dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
            focus:border-indigo-500 dark:focus:border-indigo-600
            focus:ring-indigo-500 dark:focus:ring-indigo-600
            rounded-md shadow-sm
            text-lg        /* чтобы «⋮» было видно */
            text-center    /* выравнивание по центру */
            w-6 h-6        /* маленькая ширина/высота */
            p-0            /* без внутренних отступов, чтобы селект был ровно 24×24px */
            '
    ]) }}
>
    {{-- Пункты меню --}}
    <option value="edit">Редактировать</option>
    <option value="delete">Удалить</option>
    <option value="view">Просмотр</option>
</select>
