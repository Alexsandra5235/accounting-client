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
