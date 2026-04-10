@push('styles')
    @vite('resources/css/dashboard.css')
@endpush

@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush

<x-app-layout>
    <div class="dashboard-page">
    <!-- Статистика сверху -->
    <!-- Поиск пациента -->
    <div class="card mb-6 dashboard-search-card">
        <div class="card-header dashboard-search-header">
            <h3 class="card-title dashboard-search-title">
                <i class="fas fa-search"></i>
                Поиск пациента
            </h3>
        </div>
        <div class="card-body dashboard-search-body">
            <form method="post" action="{{ route('log.search') }}" class="dashboard-search-form">
                @csrf

                <div class="dashboard-search-input-wrap">
                    <input type="text"
                           id="search_name"
                           name="search_name"
                           value="{{ $search_name ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dashboard-search-input"
                           placeholder="Поиск по ФИО пациента или номеру мед.карты"
                           autofocus>
                </div>

                <div class="flex gap-3 dashboard-search-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Найти пациента
                    </button>

                    @if(request()->has('search_name') && !empty($search_name))
                        <a href="{{ route('dashboard') }}" class="btn btn-outline">
                            <i class="fas fa-times"></i>
                            Сбросить поиск
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Сообщения об ошибках -->
    @error('error_show')
    <div class="card mb-4 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-800">Ошибка просмотра записи</h4>
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
    @enderror

    @error('error_edit')
    <div class="card mb-4 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-800">Ошибка редактирования записи</h4>
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
    @enderror

    @error('error_delete')
    <div class="card mb-4 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-800">Ошибка удаления записи</h4>
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
    @enderror

    <!-- Вкладки с пациентами -->
    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="patientTabs" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg active-tab"
                        id="current-patients-tab"
                        type="button"
                        role="tab"
                        onclick="switchTab('current')">
                    <i class="fas fa-user-injured mr-2"></i>
                    Пациенты санатория
                    <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">
                        {{ count($currentPatients) }}
                    </span>
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300"
                        id="discharged-patients-tab"
                        type="button"
                        role="tab"
                        onclick="switchTab('discharged')">
                    <i class="fas fa-user-check mr-2"></i>
                    Выписанные пациенты
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full text-xs">
                        {{ count($dischargedPatients) }}
                    </span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Вкладка Пациенты санатория -->
    <div id="tab-current" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-injured"></i>
                    Пациенты санатория
                </h3>
                @if(!empty($currentPatients))
                    <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                        {{ count($currentPatients) }} записей
                    </span>
                @endif
            </div>

            <div class="card-body">
                @if(empty($currentPatients))
                    <div class="text-center py-8">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-user-slash text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Записи не найдены</h3>
                        <p class="text-gray-500">
                            @if(request()->has('search_name') && !empty($search_name))
                                Пациенты по запросу "{{ $search_name }}" не найдены
                            @else
                                В санатории пока нет пациентов
                            @endif
                        </p>
                        <a href="{{ route('log.add') }}" class="btn btn-primary mt-4">
                            <i class="fas fa-plus"></i>
                            Добавить первого пациента
                        </a>
                    </div>
                @else
                    @include('partials.current-patients-table', ['logs' => $currentPatients])
                @endif
            </div>
        </div>
    </div>

    <!-- Вкладка Выписанные пациенты (скрыта по умолчанию) -->
    <div id="tab-discharged" class="tab-content hidden">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-check"></i>
                    Выписанные пациенты
                </h3>
                @if(!empty($dischargedPatients))
                    <span class="ml-2 px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                        {{ count($dischargedPatients) }} записей
                    </span>
                @endif
            </div>

            <div class="card-body">
                @if(empty($dischargedPatients))
                    <div class="text-center py-8">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-user-check text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Нет выписанных пациентов</h3>
                        <p class="text-gray-500">
                            @if(request()->has('search_name') && !empty($search_name))
                                Выписанные пациенты по запросу "{{ $search_name }}" не найдены
                            @else
                                В системе пока нет выписанных пациентов
                            @endif
                        </p>
                    </div>
                @else
                    @include('partials.discharged-patients-table', ['logs' => $dischargedPatients])
                @endif
            </div>
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="quick-actions mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('log.add') }}" class="card group transition-shadow">
            <div class="card-body text-center">
                <div class="quick-action-icon w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl transition-transform">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Добавить пациента</h4>
                <p class="text-sm text-gray-600">Создать новую запись о поступлении</p>
            </div>
        </a>

        <a href="{{ route('excel.store') }}" class="card group transition-shadow">
            <div class="card-body text-center">
                <div class="quick-action-icon w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white text-2xl transition-transform">
                    <i class="fas fa-file-excel"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Отчеты</h4>
                <p class="text-sm text-gray-600">Сформировать статистические отчеты</p>
            </div>
        </a>

        <a href="{{ route('patient.flow') }}" class="card group transition-shadow">
            <div class="card-body text-center">
                <div class="quick-action-icon w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center text-white text-2xl transition-transform">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Статистика</h4>
                <p class="text-sm text-gray-600">Анализ движения пациентов</p>
            </div>
        </a>
    </div>
    </div>
</x-app-layout>
