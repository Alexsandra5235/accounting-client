window.switchTab = function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach((tab) => {
        tab.classList.add('hidden');
    });

    document.getElementById(`tab-${tabName}`)?.classList.remove('hidden');

    document.querySelectorAll('#patientTabs button').forEach((btn) => {
        btn.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
        btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
    });

    const activeBtn = document.getElementById(`${tabName}-patients-tab`);
    activeBtn?.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
    activeBtn?.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
};

// resources/js/dashboard.js

// Функция переключения табов с сохранением состояния
window.switchTab = function(tabName) {
    // Скрываем все табы
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
        tab.classList.remove('active');
    });

    // Убираем активный класс со всех кнопок
    document.querySelectorAll('#patientTabs button').forEach(btn => {
        btn.classList.remove('active-tab');
        btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
    });

    // Показываем выбранный таб
    const activeTab = document.getElementById(`tab-${tabName}`);
    if (activeTab) {
        activeTab.classList.remove('hidden');
        activeTab.classList.add('active');
    }

    // Активируем соответствующую кнопку
    const activeButton = document.getElementById(`${tabName}-patients-tab`);
    if (activeButton) {
        activeButton.classList.add('active-tab');
        activeButton.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
    }

    // Сохраняем выбранный таб в localStorage
    localStorage.setItem('activePatientTab', tabName);
}

// Обработчик пагинации
window.loadPage = function(tabName, page) {
    const url = new URL(window.location.href);
    const paramName = tabName === 'current' ? 'current_page' : 'discharged_page';
    url.searchParams.set(paramName, page);

    // Сохраняем активный таб
    url.searchParams.set('active_tab', tabName);

    window.location.href = url.toString();
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Восстанавливаем активный таб
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('active_tab') || localStorage.getItem('activePatientTab') || 'current';

    switchTab(activeTab);
});
