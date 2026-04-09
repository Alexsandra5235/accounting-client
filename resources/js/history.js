// Модальные окна (оставляем без изменений)
window.openUserModal = function(user) {
    try {
        const userData = typeof user === 'string' ? JSON.parse(user) : user;
        if (!userData || !userData.id) {
            alert('Данные сотрудника отсутствуют');
            return;
        }

        let createdDate = 'Не указана';
        if (userData.created_at) {
            const date = new Date(userData.created_at);
            createdDate = date.toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        const content = `
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                                    <i class="fas fa-user text-blue-600 mr-1"></i> ФИО
                                </p>
                                <p class="font-semibold text-gray-900 text-lg">${userData.name || '—'}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                                    <i class="fas fa-envelope text-blue-600 mr-1"></i> Email
                                </p>
                                <p class="font-semibold text-gray-900">${userData.email || '—'}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                                    <i class="fas fa-id-card text-blue-600 mr-1"></i> ID сотрудника
                                </p>
                                <p class="font-semibold text-gray-900">#${userData.id || '—'}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                                    <i class="fas fa-calendar text-blue-600 mr-1"></i> Дата регистрации
                                </p>
                                <p class="font-semibold text-gray-900">${createdDate}</p>
                            </div>
                        </div>
                    </div>
                `;

        document.getElementById('userModalContent').innerHTML = content;
        document.getElementById('userModalSubtitle').innerHTML = `ID: ${userData.id}`;
        document.getElementById('editUserProfileLink').href = `/platform/systems/users/edit/${userData.id}`;
        document.getElementById('userInfoModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } catch (error) {
        console.error('Ошибка в openUserModal:', error);
        alert('Не удалось загрузить информацию о сотруднике');
    }
}

window.closeUserModal = function() {
    document.getElementById('userInfoModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.openChangesModal = function(diff) {
    try {
        const changes = typeof diff === 'string' ? JSON.parse(diff) : diff;

        if (!changes || Object.keys(changes).length === 0) {
            document.getElementById('changesModalContent').innerHTML = `
                        <div class="text-center py-10">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-info-circle text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium">Нет данных об изменениях</p>
                            <p class="text-gray-400 text-sm mt-1">Запись была создана или изменения не отслеживаются</p>
                        </div>
                    `;
        } else {
            let tableHTML = `
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Поле</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-red-50">Было</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-green-50">Стало</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                    `;

            let hasChanges = false;
            const fieldMap = {
                'name': 'ФИО пациента', 'birth_day': 'Дата рождения', 'gender': 'Пол',
                'medical_card': 'Номер мед. карты', 'passport': 'Паспорт', 'nationality': 'Гражданство',
                'address': 'Адрес регистрации', 'register_place': 'Адрес пребывания',
                'phone_agent': 'Телефон представителя', 'delivered': 'Доставлен',
                'diagnosis': 'Диагноз', 'outcome': 'Исход'
            };

            Object.entries(changes).forEach(([field, values]) => {
                if (values && (values.before !== undefined || values.after !== undefined)) {
                    hasChanges = true;
                    const before = values.before !== null && values.before !== undefined ? values.before : '—';
                    const after = values.after !== null && values.after !== undefined ? values.after : '—';
                    const fieldName = fieldMap[field] || field;

                    tableHTML += `
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 text-sm font-semibold text-gray-900">${fieldName}</td>
                                    <td class="px-5 py-4 text-sm text-gray-800 bg-red-50 border-l border-red-100">${before}</td>
                                    <td class="px-5 py-4 text-sm text-gray-800 bg-green-50 border-l border-green-100">${after}</td>
                                </tr>
                            `;
                }
            });

            tableHTML += `</tbody></table></div>`;

            if (!hasChanges) {
                tableHTML = `<div class="text-center py-10"><div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center"><i class="fas fa-info-circle text-gray-400 text-3xl"></i></div><p class="text-gray-600 font-medium">Нет изменений для отображения</p></div>`;
            }

            document.getElementById('changesModalContent').innerHTML = tableHTML;
        }

        document.getElementById('changesInfoModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } catch (error) {
        console.error('Ошибка в openChangesModal:', error);
        document.getElementById('changesModalContent').innerHTML = `<div class="text-center py-10 text-red-600">Ошибка при загрузке данных</div>`;
        document.getElementById('changesInfoModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

window.closeChangesModal = function() {
    document.getElementById('changesInfoModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.addEventListener('click', function(event) {
    if (event.target === document.getElementById('userInfoModal')) closeUserModal();
    if (event.target === document.getElementById('changesInfoModal')) closeChangesModal();
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeUserModal();
        closeChangesModal();
    }
});

// Автоматическая отправка формы поиска при вводе (с задержкой)
let searchTimeout;
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });
}
