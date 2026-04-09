// Показать/скрыть поле медицинской организации при выборе перевода
document.addEventListener('DOMContentLoaded', function() {
    const outcomeSelect = document.getElementById('outcome');
    const medicalOrgField = document.getElementById('medicalOrgField');

    if (outcomeSelect) {
        outcomeSelect.addEventListener('change', function() {
            if (this.value === 'переведен в другую медицинскую организацию') {
                medicalOrgField.classList.remove('hidden');
                medicalOrgField.querySelector('input').required = true;
            } else {
                medicalOrgField.classList.add('hidden');
                medicalOrgField.querySelector('input').required = false;
            }
        });

        // Инициализация при загрузке
        if (outcomeSelect.value === 'переведен в другую медицинскую организацию') {
            medicalOrgField.classList.remove('hidden');
            medicalOrgField.querySelector('input').required = true;
        }
    }
});

function confirmDeletion(patientName) {
    return confirm(`Вы уверены, что хотите удалить запись пациента "${patientName}"?\n\nЭто действие невозможно будет отменить.`);
}

// Форматирование СНИЛС
document.getElementById('snils')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 9) {
        value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1-$2-$3 $4');
    } else if (value.length > 6) {
        value = value.replace(/^(\d{3})(\d{3})(\d{0,3})$/, '$1-$2-$3');
    } else if (value.length > 3) {
        value = value.replace(/^(\d{3})(\d{0,3})$/, '$1-$2');
    }

    e.target.value = value;
});

// Форматирование полиса
document.getElementById('polis')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 16) value = value.slice(0, 16);

    if (value.length > 4) {
        value = value.replace(/^(\d{4})(\d{0,12})$/, '$1 $2');
    }

    e.target.value = value;
});
