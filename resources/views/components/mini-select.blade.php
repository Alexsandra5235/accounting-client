@props([
    'editRoute',
    'viewRoute',
    'recordId',
    'recordName',
])

<select
    x-data
    onchange="(function(sel){
        const val = sel.value;
        if (!val) {
            sel.selectedIndex = 0;
            return;
        }
        if (val === 'edit') {
            window.location.href = '{{ $editRoute }}';
        }
        if (val === 'view') {
            window.location.href = '{{ $viewRoute }}';
        }
        if (val === 'delete') {
            if (confirm('Вы уверены, что хотите удалить запись пациента «{{ addslashes($recordName) }}»? Это действие невозможно будет отменить.')) {
                document.getElementById('delete-form-{{ $recordId }}').submit();
            }
        }
        sel.selectedIndex = 0;
    })(this)"
    {{ $attributes->merge([
        'class' => '
            border-gray-300
            dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
            focus:border-indigo-500 dark:focus:border-indigo-600
            focus:ring-indigo-500 dark:focus:ring-indigo-600
            rounded-md shadow-sm
            text-lg
            text-left
            w-6
            h-6
            p-1
        '
    ]) }}
>
    {{-- Пустая опция, чтобы ничего не отображалось как выбор по умолчанию --}}
    <option value="" hidden disabled selected>⚙️</option>
    <option value="edit">✏️ Редактировать</option>
    <option value="delete">🗑️ Удалить</option>
    <option value="view">👁️ Просмотр</option>
</select>

{{-- Форма удаления --}}
<form id="delete-form-{{ $recordId }}" method="POST" action="{{ route('log.destroy', ['id' => $recordId]) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
