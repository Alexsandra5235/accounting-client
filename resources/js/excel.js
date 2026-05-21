window.showHelpModal = function() {
    document.getElementById('helpModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

window.closeHelpModal = function() {
    document.getElementById('helpModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Валидация дат
document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];

    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            const form = this.closest('form');
            const date1 = form.querySelector('input[name="date1"]');
            const date2 = form.querySelector('input[name="date2"]');

            if (date1 && date2 && date1.value && date2.value) {
                if (date2.value < date1.value) {
                    alert('Дата окончания не может быть раньше даты начала');
                    date2.value = date1.value;
                }

                // Проверка на период > 31 дня
                const start = new Date(date1.value);
                const end = new Date(date2.value);
                const diffTime = Math.abs(end - start);
            }
        });
    });
});
