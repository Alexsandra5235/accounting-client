// resources/js/import.js

document.addEventListener('DOMContentLoaded', function() {
    // === DOM элементы ===
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const previewList = document.getElementById('previewList');
    const fileCount = document.getElementById('fileCount');
    const processBtn = document.getElementById('processBtn');
    const clearFilesBtn = document.getElementById('clearFilesBtn');
    const addMoreBtn = document.getElementById('addMoreBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');

    // Шаги
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const progressFill = document.getElementById('progressFill');
    const stepIndicators = document.querySelectorAll('.step-indicator');

    // Шаг 2 — карточки пациентов
    const patientCards = document.getElementById('patientCards');
    const totalPatients = document.getElementById('totalPatients');
    const backToStep1Btn = document.getElementById('backToStep1Btn');
    const goToStep3Btn = document.getElementById('goToStep3Btn');

    // Шаг 3
    const summaryFiles = document.getElementById('summaryFiles');
    const summaryRecords = document.getElementById('summaryRecords');
    const summaryEdited = document.getElementById('summaryEdited');
    const backToStep2Btn = document.getElementById('backToStep2Btn');
    const saveDataBtn = document.getElementById('saveDataBtn');

    // Модальное окно фото
    const photoModal = document.getElementById('photoModal');
    const photoModalImage = document.getElementById('photoModalImage');
    const photoModalTitle = document.getElementById('photoModalTitle');
    const closePhotoModalBtn = document.getElementById('closePhotoModalBtn');

    // === Состояние ===
    let files = [];
    let processedData = null;
    let editedCount = 0;

    // Словари
    const GENDER_OPTIONS = [
        { value: 'male', label: 'Мужской' },
        { value: 'female', label: 'Женский' }
    ];

    const NATIONALITY_OPTIONS = [
        { value: 'РФ', label: 'РФ' },
        { value: 'РБ', label: 'РБ' },
        { value: 'Другое', label: 'Другое' }
    ];

    // Получаем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // === Инициализация ===
    dropZone.addEventListener('click', () => fileInput.click());
    addMoreBtn.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

    clearFilesBtn.addEventListener('click', () => {
        files = [];
        updatePreview();
    });

    // === Функции работы с файлами ===
    function handleFiles(newFiles) {
        const validFiles = Array.from(newFiles).filter(file => {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            const maxSize = 10 * 1024 * 1024;
            if (!validTypes.includes(file.type)) {
                showNotification(`Файл ${file.name} не поддерживается`, 'error');
                return false;
            }
            if (file.size > maxSize) {
                showNotification(`Файл ${file.name} превышает 10MB`, 'error');
                return false;
            }
            return true;
        });

        files = [...files, ...validFiles];
        updatePreview();
    }

    function updatePreview() {
        previewList.innerHTML = '';
        if (files.length === 0) {
            previewContainer.classList.add('hidden');
            processBtn.disabled = true;
            return;
        }
        previewContainer.classList.remove('hidden');
        fileCount.textContent = files.length;
        processBtn.disabled = false;

        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = createPreviewItem(file, index, e.target.result);
                    previewList.appendChild(preview);
                };
                reader.readAsDataURL(file);
            } else {
                const preview = createPreviewItem(file, index, null);
                previewList.appendChild(preview);
            }
        });
    }

    function createPreviewItem(file, index, dataUrl) {
        const div = document.createElement('div');
        div.className = 'preview-item';
        div.innerHTML = `
            <div class="preview-item-image">
                ${dataUrl
            ? `<img src="${dataUrl}" alt="${file.name}">`
            : `<div class="preview-pdf-icon"><i class="fas fa-file-pdf"></i></div>`
        }
            </div>
            <div class="preview-item-info">
                <p class="preview-item-name">${escapeHtml(file.name)}</p>
                <p class="preview-item-size">${formatFileSize(file.size)}</p>
            </div>
            <button class="preview-item-remove" data-index="${index}">
                <i class="fas fa-times"></i>
            </button>
        `;
        div.querySelector('.preview-item-remove').addEventListener('click', (e) => {
            e.stopPropagation();
            files.splice(index, 1);
            updatePreview();
        });
        return div;
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    // === Обработка файлов ===
    processBtn.addEventListener('click', async () => {
        if (files.length === 0) return;
        progressContainer.classList.remove('hidden');
        processBtn.disabled = true;

        try {
            await processFiles();
        } catch (error) {
            showNotification('Ошибка обработки: ' + error.message, 'error');
        } finally {
            progressContainer.classList.add('hidden');
            processBtn.disabled = false;
        }
    });

    async function processFiles() {
        const formData = new FormData();
        files.forEach(file => formData.append('files[]', file));

        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 3;
            if (progress > 90) {
                clearInterval(progressInterval);
                progress = 90;
            }
            updateProgress(progress);
        }, 500);

        try {
            const response = await fetch('/import/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            clearInterval(progressInterval);

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || 'Ошибка сервера');
            }

            const data = await response.json();
            updateProgress(100);

            if (!data.success) {
                throw new Error('Ошибка обработки файлов');
            }

            if (data.errors && data.errors.length > 0) {
                data.errors.forEach(err => {
                    showNotification(`Файл "${err.filename}": ${err.error}`, 'error');
                });
            }

            if (data.results && data.results.length > 0) {
                processedData = {
                    fileData: data.results.map(result => ({
                        filename: result.source_file || 'Неизвестный файл',
                        dataUrl: result.preview_url || '',
                        patient: {
                            id: result.id || `pat_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
                            date_receipt: result.date_receipt || '',
                            name: result.name || '',
                            birth_day: result.birth_day || '',
                            gender: result.gender || '',
                            medical_card: result.medical_card || '',
                            passport: result.passport || '',
                            nationality: result.nationality || '',
                            address: result.address || '',
                            register_place: result.register_place || '',
                            phone_agent: result.phone_agent || '',
                            snils: result.snils || '',
                            polis: result.polis || '',
                            diagnosis_state: result.diagnosis_state || '',
                            diagnosis_wound: result.diagnosis_wound || '',
                            confidence: result.confidence || 0,
                            log_receipt: result.log_receipt || null,
                            _edited: false
                        }
                    }))
                };

                showNotification(
                    `Успешно обработано: ${data.processed} из ${data.total} файлов`,
                    data.failed > 0 ? 'warning' : 'success'
                );

                goToStep(2);
            } else {
                showNotification('Не удалось распознать данные. Попробуйте другие файлы.', 'error');
            }

        } catch (error) {
            clearInterval(progressInterval);
            updateProgress(0);
            console.error('Process error:', error);
            throw error;
        }
    }

    function updateProgress(percent) {
        progressBar.style.width = percent + '%';
        progressText.textContent = Math.round(percent) + '%';
    }

    // === Навигация по шагам ===
    function goToStep(step) {
        [step1, step2, step3].forEach(el => el.classList.add('hidden'));
        stepIndicators.forEach(el => el.classList.remove('active', 'completed'));

        if (step === 1) {
            step1.classList.remove('hidden');
            stepIndicators[0].classList.add('active');
            progressFill.style.width = '33.33%';
        } else if (step === 2) {
            step2.classList.remove('hidden');
            stepIndicators[0].classList.add('completed');
            stepIndicators[1].classList.add('active');
            progressFill.style.width = '66.66%';
            renderStep2();
        } else if (step === 3) {
            step3.classList.remove('hidden');
            stepIndicators[0].classList.add('completed');
            stepIndicators[1].classList.add('completed');
            stepIndicators[2].classList.add('active');
            progressFill.style.width = '100%';
            renderStep3();
        }
    }

    backToStep1Btn.addEventListener('click', () => goToStep(1));
    goToStep3Btn.addEventListener('click', () => goToStep(3));
    backToStep2Btn.addEventListener('click', () => goToStep(2));

    // Исправленная функция searchMkb
    async function searchMkb(query, type, inputElement) {
        if (!query || query.length < 2) return [];

        const url = type === 'state'
            ? '/mkd/suggestions/state'
            : '/mkd/suggestions/wound';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ query: query })
            });

            console.log('API response:', response);

            if (!response.ok) return [];

            const data = await response.json();

            console.log('API response:', data);

            // Универсальная обработка ответа API
            let suggestions = [];

            // Если data - массив
            if (Array.isArray(data)) {
                suggestions = data;
            }
            // Если data.suggestions - массив
            else if (data.suggestions && Array.isArray(data.suggestions)) {
                suggestions = data.suggestions;
            }
            // Если data.data - массив
            else if (data.data && Array.isArray(data.data)) {
                suggestions = data.data;
            }
            // Если data.classifiers - массив
            else if (data.classifiers && Array.isArray(data.classifiers)) {
                suggestions = data.classifiers;
            }
            // Если data.items - массив
            else if (data.items && Array.isArray(data.items)) {
                suggestions = data.items;
            }

            // Преобразуем каждый элемент в единый формат
            return suggestions.map(item => {
                // Если item уже объект с code и name
                if (typeof item === 'object' && item !== null) {
                    return {
                        code: item.code || item.value || item.id || '',
                        name: item.name || item.title || item.description || '',
                        original: item
                    };
                }
                // Если item - строка
                if (typeof item === 'string') {
                    return {
                        code: item,
                        name: '',
                        original: item
                    };
                }
                return {
                    code: '',
                    name: '',
                    original: item
                };
            }).filter(s => s.code); // Убираем пустые

        } catch (error) {
            console.error('MKB search error:', error);
            return [];
        }
    }

// Исправленная функция renderMkbDropdown
    function renderMkbDropdown(inputElement, suggestions, fieldType) {
        const wrapper = inputElement.closest('.mkb-wrapper');
        if (!wrapper) return;

        let dropdown = wrapper.querySelector('.mkb-dropdown');

        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.className = 'mkb-dropdown hidden';
            wrapper.appendChild(dropdown);
        }

        if (!suggestions || suggestions.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }

        dropdown.innerHTML = suggestions.map(suggestion => {
            const code = suggestion.code || suggestion.value || '';
            const name = suggestion.name || '';
            return `
            <div class="mkb-item" data-code="${escapeHtml(code)}" data-name="${escapeHtml(name)}">
                ${code ? `<span class="mkb-code">${escapeHtml(code)}</span>` : ''}
                ${name ? `<span class="mkb-name">${escapeHtml(name)}</span>` : ''}
            </div>
        `;
        }).join('');

        dropdown.classList.remove('hidden');

        dropdown.querySelectorAll('.mkb-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const code = item.dataset.code;
                if (code) {
                    inputElement.value = code;
                } else {
                    const name = item.dataset.name;
                    if (name) inputElement.value = name;
                }
                dropdown.classList.add('hidden');

                // Триггерим событие изменения
                const event = new Event('change', { bubbles: true });
                inputElement.dispatchEvent(event);
            });
        });
    }

    function initMkbAutocomplete(field, fieldName, fieldType) {
        let debounceTimer;

        field.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value;

            if (query.length < 2) {
                const wrapper = field.closest('.mkb-wrapper');
                const dropdown = wrapper?.querySelector('.mkb-dropdown');
                if (dropdown) dropdown.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(async () => {
                const suggestions = await searchMkb(query, fieldType, field);
                renderMkbDropdown(field, suggestions, fieldType);
            }, 300);
        });

        // Закрываем dropdown при потере фокуса
        field.addEventListener('blur', () => {
            setTimeout(() => {
                const wrapper = field.closest('.mkb-wrapper');
                const dropdown = wrapper?.querySelector('.mkb-dropdown');
                if (dropdown) dropdown.classList.add('hidden');
            }, 200);
        });
    }

    // === Шаг 2: Карточки пациентов ===
    function renderStep2() {
        if (!processedData || !processedData.fileData || processedData.fileData.length === 0) {
            patientCards.innerHTML = '<p class="text-gray-500 text-center py-8">Нет данных для отображения</p>';
            totalPatients.textContent = '0';
            return;
        }

        patientCards.innerHTML = '';
        let totalPatientsCount = processedData.fileData.length;

        processedData.fileData.forEach((fileData, fileIndex) => {
            const patient = fileData.patient;
            const card = createPatientCard(patient, fileIndex, fileData.dataUrl, fileData.filename);
            patientCards.appendChild(card);
        });

        totalPatients.textContent = totalPatientsCount;
        editedCount = 0;
    }

    function createPatientCard(patient, fileIndex, dataUrl, filename) {
        const card = document.createElement('div');
        card.className = 'patient-card';

        const hasName = patient.name && patient.name.trim() !== '';

        card.innerHTML = `
                     <div class="patient-card-header">
              <div class="patient-card-header-left">
                <div class="patient-photo-thumb">
                  ${dataUrl
                        ? `<img src="${dataUrl}" alt="${filename}">
                       <span class="thumb-overlay"><i class="fas fa-search-plus"></i></span>`
                        : `<div class="preview-pdf-icon"><i class="fas fa-file-pdf"></i></div>`
                    }
                </div>
                <div class="patient-card-title">
                  <span class="patient-card-name ${hasName ? '' : 'empty'}">
                    ${hasName ? escapeHtml(patient.name) : 'Имя не распознано'}
                  </span>
                  <span class="patient-card-filename">
                    <i class="fas fa-file-image"></i>
                    ${escapeHtml(filename)}
                    ${dataUrl ? `
                      <button class="btn-view-photo"
                              data-photo="${dataUrl}"
                              data-filename="${escapeHtml(filename)}"
                              title="Открыть фото для сверки">
                        <i class="fas fa-up-right-and-down-left-from-center"></i>
                      </button>` : ''}
                  </span>
                </div>
              </div>
              <button class="patient-card-toggle open">
                <i class="fas fa-chevron-down"></i>
              </button>
            </div>
            <div class="patient-card-body">
                <div class="patient-fields-grid">
                    ${renderPatientFields(patient, fileIndex)}
                </div>
                <div class="patient-card-actions">
                    <div class="patient-confidence">
                        <span class="patient-confidence-label">Точность распознавания:</span>
                        <span class="confidence-badge confidence-${getConfidenceClass(patient.confidence)}">
                            ${patient.confidence}%
                        </span>
                    </div>
                </div>
            </div>
        `;

        // Переключение сворачивания
        const header = card.querySelector('.patient-card-header');
        const body = card.querySelector('.patient-card-body');
        const toggle = card.querySelector('.patient-card-toggle');

        if (!header || !body || !toggle) {
            console.error('Проблема с разметкой карточки', { header, body, toggle, html: card.outerHTML });
        } else {
            const setOpen = (isOpen) => {
                body.classList.toggle('hidden', !isOpen);
                toggle.classList.toggle('open', isOpen);
            };

            setOpen(true);

            header.addEventListener('click', (e) => {
                if (e.target.closest('.btn-view-photo')) return;
                if (e.target.closest('.mkb-item')) return;
                if (e.target.closest('.patient-card-toggle')) return;
                setOpen(body.classList.contains('hidden'));
            });

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                setOpen(body.classList.contains('hidden'));
            });
        }

        // Инициализация автодополнения для МКБ полей
        const stateInput = card.querySelector('[data-field="diagnosis_state"]');
        const woundInput = card.querySelector('[data-field="diagnosis_wound"]');

        if (stateInput) initMkbAutocomplete(stateInput, 'diagnosis_state', 'state');
        if (woundInput) initMkbAutocomplete(woundInput, 'diagnosis_wound', 'wound');

        // Отслеживание изменений
        const inputs = card.querySelectorAll('.patient-field-input, .patient-field-select');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                input.classList.add('changed');
                updatePatientName(card, patient);
                updatePatientData(input, patient);
                patient._edited = true;
                updateEditedCount();
            });
            input.addEventListener('change', () => {
                input.classList.add('changed');
                updatePatientName(card, patient);
                updatePatientData(input, patient);
                patient._edited = true;
                updateEditedCount();
            });
        });

        // Кнопка просмотра фото
        const viewPhotoBtn = card.querySelector('.btn-view-photo');
        if (viewPhotoBtn && dataUrl) {
            viewPhotoBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                openPhotoModal(dataUrl, filename);
            });
        }

        return card;
    }

    function updatePatientName(card, patient) {
        const nameInput = card.querySelector('[data-field="name"]');
        const nameSpan = card.querySelector('.patient-card-name');
        if (nameInput && nameSpan) {
            const newName = nameInput.value.trim();
            nameSpan.textContent = newName || 'Имя не распознано';
            nameSpan.classList.toggle('empty', !newName);
            patient.name = newName;
        }
    }

    function updatePatientData(input, patient) {
        const field = input.dataset.field;
        const value = input.value;

        if (field === 'diagnosis_state') {
            patient.diagnosis_state = value;
        } else if (field === 'diagnosis_wound') {
            patient.diagnosis_wound = value;
        } else if (field.startsWith('receipt_') && patient.log_receipt) {
            const receiptField = field.replace('receipt_', '');
            patient.log_receipt[receiptField] = value;
        } else {
            patient[field] = value;
        }
    }

    function updateEditedCount() {
        let count = 0;
        if (processedData && processedData.fileData) {
            processedData.fileData.forEach(fd => {
                if (fd.patient._edited) count++;
            });
        }
        editedCount = count;
    }

    // === Рендер полей ===
    function renderPatientFields(patient, fileIndex) {
        let html = '';
        const lr = patient.log_receipt || {};

        function toDateInputValue(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('.');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }

        // Основные поля
        html += renderField('Дата поступления', 'date_receipt', toDateInputValue(lr.date_receipt), 'date', true);
        html += renderField('Время поступления', 'time_receipt', lr.time_receipt, 'time', true);
        html += renderField('Фамилия Имя Отчество', 'name', patient.name, 'text', true);
        html += renderField('Дата рождения', 'birth_day', toDateInputValue(patient.birth_day), 'date', true);
        html += renderSelectField('Пол', 'gender', patient.gender, GENDER_OPTIONS, true);
        html += renderField('Номер мед. карты', 'medical_card', patient.medical_card, 'text', true);
        html += renderField('Паспорт', 'passport', patient.passport, 'text');
        html += renderSelectField('Гражданство', 'nationality', patient.nationality, NATIONALITY_OPTIONS);
        html += renderField('Адрес регистрации по месту жительства', 'address', patient.address, 'text');
        html += renderField('Адрес регистрации по месту пребывания', 'register_place', patient.register_place, 'text');
        html += renderField('СНИЛС', 'snils', patient.snils, 'text');
        html += renderField('Полис ОМС', 'polis', patient.polis, 'text');

        // Диагноз с автодополнением
        html += `
            <div class="diagnosis-block">
                <div class="diagnosis-block-title">Диагноз</div>
                ${renderMkbField('Диагноз заболевания (код по МКБ)', 'diagnosis_state', patient.diagnosis_state || '')}
                ${renderMkbField('Причина травмы/отравления (код по МКБ)', 'diagnosis_wound', patient.diagnosis_wound || '')}
            </div>
        `;

        // Данные поступления
        html += renderField('Факт употребления алкоголя/ПАВ', 'receipt_datetime_alcohol', lr.datetime_alcohol, 'text');
        html += renderField('Телефон представителя', 'phone_agent', patient.phone_agent, 'text');
        html += renderField('Доставлен (направлен)', 'receipt_delivered', lr.delivered, 'text');

        return html;
    }

    function renderField(label, fieldName, value, type = 'text', required = false) {
        return `
            <div class="patient-field">
                <label class="patient-field-label">
                    ${escapeHtml(label)}
                    ${required ? '<span class="required-star">*</span>' : ''}
                </label>
                <input type="${type}"
                       class="patient-field-input"
                       data-field="${fieldName}"
                       value="${escapeHtml(value || '')}"
                       placeholder="${required ? 'Обязательно' : 'Не указано'}"
                       required
                       >
            </div>
        `;
    }

    function renderMkbField(label, fieldName, value) {
        return `
            <div class="patient-field">
                <label class="patient-field-label">${escapeHtml(label)}</label>
                <div class="mkb-wrapper">
                    <input type="text"
                           class="patient-field-input mkb-input"
                           data-field="${fieldName}"
                           value="${escapeHtml(value || '')}"
                           placeholder="Начните вводить код или название диагноза"
                           autocomplete="off">
                </div>
            </div>
        `;
    }

    function renderSelectField(label, fieldName, value, options, required = false) {
        let optionsHtml = '<option value="">Не выбрано</option>';
        options.forEach(opt => {
            const selected = value === opt.value ? 'selected' : '';
            optionsHtml += `<option value="${opt.value}" ${selected}>${escapeHtml(opt.label)}</option>`;
        });

        return `
            <div class="patient-field">
                <label class="patient-field-label">
                    ${escapeHtml(label)}
                    ${required ? '<span class="required-star">*</span>' : ''}
                </label>
                <select class="patient-field-select" data-field="${fieldName}">
                    ${optionsHtml}
                </select>
            </div>
        `;
    }

    function getConfidenceClass(confidence) {
        if (confidence >= 90) return 'high';
        if (confidence >= 70) return 'medium';
        return 'low';
    }

    // === Модальное окно фото ===
    function openPhotoModal(dataUrl, filename) {
        if (!dataUrl) return;
        photoModalImage.src = dataUrl;
        photoModalTitle.textContent = filename;
        photoModal.classList.remove('hidden');
    }

    closePhotoModalBtn.addEventListener('click', () => photoModal.classList.add('hidden'));
    photoModal.addEventListener('click', (e) => {
        if (e.target === photoModal) photoModal.classList.add('hidden');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !photoModal.classList.contains('hidden')) {
            photoModal.classList.add('hidden');
        }
    });

    // === Шаг 3: Итоги ===
    function renderStep3() {
        if (!processedData || !processedData.fileData) return;

        const totalFiles = processedData.fileData.length;
        summaryFiles.textContent = totalFiles;
        summaryRecords.textContent = totalFiles;
        summaryEdited.textContent = editedCount;
    }

    saveDataBtn.addEventListener('click', () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/import/save';
        form.style.display = 'none';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = csrfToken;
        form.appendChild(csrf);

        const cards = document.querySelectorAll('#patientCards .patient-card');
        const patients = [];

        cards.forEach((card) => {
            const patient = {};
            const inputs = card.querySelectorAll('input[data-field], select[data-field]');
            inputs.forEach(input => {
                patient[input.getAttribute('data-field')] = input.value;
            });
            patients.push(patient);
        });

        const patientsInput = document.createElement('input');
        patientsInput.type = 'hidden';
        patientsInput.name = 'patients';
        patientsInput.value = JSON.stringify(patients);
        form.appendChild(patientsInput);

        document.body.appendChild(form);
        form.submit();
    });

    // === Уведомления ===
    function showNotification(message, type) {
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();

        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle'
        };

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${icons[type] || 'info-circle'}"></i>
                <span>${escapeHtml(message)}</span>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // === Утилиты ===
    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
});
