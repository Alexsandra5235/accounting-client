@push('styles')
    @vite('resources/css/import.css')
@endpush

@push('scripts')
    @vite('resources/js/import.js')
@endpush

<x-app-layout>
    <div class="import-page">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Индикатор шагов -->
                <div class="steps-progress mb-6">
                    <div class="steps-progress-bar">
                        <div class="steps-progress-fill" id="progressFill"></div>
                    </div>
                    <div class="steps-items">
                        <div class="step-indicator active" data-step="1">
                            <div class="step-circle">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="step-label">
                                <span class="step-name">Шаг 1 - Загрузка документов</span>
                            </div>
                        </div>
                        <div class="step-indicator" data-step="2">
                            <div class="step-circle">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="step-label">
                                <span class="step-name">Шаг 2 - Проверка данных</span>
                            </div>
                        </div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-circle">
                                <i class="fas fa-save"></i>
                            </div>
                            <div class="step-label">
                                <span class="step-name">Шаг 3 - Сохранение</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Шаг 1: Загрузка документов -->
                <div id="step1" class="step-content active">
                    <div class="import-card">
                        <div class="import-card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-file-import text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Загрузка документов</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Загрузите фото или сканы медицинских карт для распознавания
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="import-card-body">
                            <!-- Drag & Drop зона -->
                            <div id="dropZone" class="upload-zone">
                                <div class="upload-zone-content">
                                    <div class="upload-zone-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <h4 class="upload-zone-title">Перетащите файлы сюда</h4>
                                    <p class="upload-zone-subtitle">или нажмите для выбора</p>
                                    <p class="upload-zone-formats">
                                        Поддерживаемые форматы:
                                        <span class="format-badge">JPG</span>
                                        <span class="format-badge">PNG</span>
                                        <span class="format-badge">PDF</span>
                                    </p>
                                    <p class="upload-zone-limit">Максимальный размер файла: 10 МБ</p>
                                </div>
                                <input type="file"
                                       id="fileInput"
                                       multiple
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       class="hidden">
                            </div>

                            <!-- Предпросмотр -->
                            <div id="previewContainer" class="preview-container hidden">
                                <div class="preview-header">
                                    <h4 class="preview-title">
                                        <i class="fas fa-images mr-2"></i>
                                        Загруженные файлы (<span id="fileCount">0</span>)
                                    </h4>
                                    <div class="flex gap-2">
                                        <button id="addMoreBtn" class="btn btn-outline btn-sm">
                                            <i class="fas fa-plus mr-1"></i>
                                            Добавить
                                        </button>
                                        <button id="clearFilesBtn" class="btn btn-outline btn-sm text-red-600">
                                            <i class="fas fa-trash mr-1"></i>
                                            Очистить
                                        </button>
                                    </div>
                                </div>
                                <div id="previewList" class="preview-list"></div>
                            </div>

                            <!-- Прогресс -->
                            <div id="progressContainer" class="progress-container hidden">
                                <div class="progress-header">
                                    <h4 class="progress-title">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Распознавание документов...
                                    </h4>
                                    <span id="progressText" class="progress-text">0%</span>
                                </div>
                                <div class="progress-bar">
                                    <div id="progressBar" class="progress-fill" style="width: 0"></div>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="step-actions">
                                <div></div>
                                <button id="processBtn" class="btn btn-primary" disabled>
                                    <i class="fas fa-cogs mr-2"></i>
                                    Распознать документы
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Инструкция -->
                    <div class="import-card mt-6">
                        <div class="import-card-header">
                            <h3 class="import-card-title">
                                <i class="fas fa-info-circle mr-2"></i>
                                Как это работает
                            </h3>
                        </div>
                        <div class="import-card-body">
                            <div class="steps-grid">
                                <div class="step-item">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h4 class="step-title">Загрузите файлы</h4>
                                        <p class="step-desc">Перетащите или выберите фото/сканы медицинских карт</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h4 class="step-title">Дождитесь обработки</h4>
                                        <p class="step-desc">Система автоматически распознает данные с помощью ИИ</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h4 class="step-title">Проверьте данные</h4>
                                        <p class="step-desc">Проверьте распознанные данные и подтвердите импорт</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Шаг 2: Проверка и редактирование данных -->
                <div id="step2" class="step-content hidden">
                    <div class="import-card">
                        <div class="import-card-header">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-search text-green-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Проверка распознанных данных</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Проверьте и при необходимости отредактируйте данные пациентов
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <span class="status-badge status-success">
                                        <i class="fas fa-check mr-1"></i>
                                        <span id="totalPatients">0</span> пациентов
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="import-card-body">
                            <!-- Группировка по файлам (1 файл = 1 пациент) -->
                            <div id="patientCards" class="patient-cards">
                                <!-- Динамически заполняется -->
                            </div>
                            <div class="step-actions">
                                <button id="backToStep1Btn" class="btn btn-outline">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Назад к загрузке
                                </button>
                                <button id="goToStep3Btn" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Подтвердить данные
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Шаг 3: Сохранение -->
                <div id="step3" class="step-content hidden">
                    <div class="import-card">
                        <div class="import-card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-save text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Сохранение данных</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Проверьте итоговую информацию и подтвердите импорт
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="import-card-body">
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <div class="summary-icon bg-blue-100">
                                        <i class="fas fa-file-image text-blue-600"></i>
                                    </div>
                                    <div class="summary-info">
                                        <span class="summary-label">Обработано файлов</span>
                                        <span id="summaryFiles" class="summary-value">0</span>
                                    </div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-icon bg-green-100">
                                        <i class="fas fa-list text-green-600"></i>
                                    </div>
                                    <div class="summary-info">
                                        <span class="summary-label">Всего записей</span>
                                        <span id="summaryRecords" class="summary-value">0</span>
                                    </div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-icon bg-yellow-100">
                                        <i class="fas fa-edit text-yellow-600"></i>
                                    </div>
                                    <div class="summary-info">
                                        <span class="summary-label">Отредактировано</span>
                                        <span id="summaryEdited" class="summary-value">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="step-actions">
                                <button id="backToStep2Btn" class="btn btn-outline">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Назад к проверке
                                </button>
                                <button id="saveDataBtn" class="btn btn-primary">
                                    <i class="fas fa-check mr-2"></i>
                                    Сохранить все данные
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно просмотра фото -->
    <div id="photoModal" class="modal-overlay hidden">
        <div class="modal-photo-container">
            <div class="modal-photo-header">
                <h3 id="photoModalTitle" class="modal-title">Просмотр документа</h3>
                <button id="closePhotoModalBtn" class="modal-close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-photo-body">
                <img id="photoModalImage" src="" alt="Документ" class="modal-photo-image">
            </div>
        </div>
    </div>
</x-app-layout>
