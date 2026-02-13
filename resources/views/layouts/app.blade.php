<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Система учета пациентов санатория "Журавлик"</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #60a5fa;
            --primary-dark: #2563eb;
            --secondary: #10b981;
            --secondary-light: #34d399;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius: 0.5rem;
            --radius-sm: 0.375rem;
            --radius-lg: 0.75rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #f1f5f9 100%);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: var(--radius);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: var(--radius);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Layout */
        .app-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .main-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--gray-900);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-link {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: var(--primary);
            background: var(--gray-50);
        }

        .nav-link.active {
            color: var(--primary);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
        }

        .nav-link i {
            font-size: 1rem;
        }

        /* User Menu */
        .user-menu {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius);
            background: white;
            border: 1px solid var(--gray-200);
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-btn:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-name {
            font-weight: 500;
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .user-menu:hover .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--gray-100);
        }

        .dropdown-link:last-child {
            border-bottom: none;
        }

        .dropdown-link:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .dropdown-link i {
            width: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--gray-500);
            font-size: 1rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            background: linear-gradient(135deg, var(--gray-50), white);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title i {
            color: var(--primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary), #0da271);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #0da271, var(--secondary));
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.2));
            color: var(--primary);
        }

        .stat-icon.green {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.2));
            color: var(--secondary);
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.2));
            color: #8b5cf6;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        /* Footer */
        .main-footer {
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 2rem 1.5rem;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--gray-500);
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-link {
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .footer-link:hover {
            color: var(--primary);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .mobile-nav {
            display: none;
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 1rem;
            box-shadow: var(--shadow);
        }

        .mobile-nav.active {
            display: block;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--radius);
            margin-bottom: 0.25rem;
        }

        .mobile-nav-link:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .mobile-nav-link.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .nav-menu {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .header-content {
                padding: 0 1rem;
            }

            .main-content {
                padding: 1.5rem 1rem;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .footer-links {
                justify-content: center;
                flex-wrap: wrap;
            }
        }



        #toast-container > div {
            opacity: 1 !important;
        }

        /* И убираем возможные кастомные прозрачности */
        .toast-success,
        .toast-warning,
        .toast-info,
        .toast-error {
            opacity: 1 !important;
        }

    </style>
</head>
<body class="font-sans antialiased">
<div class="app-container">
    <!-- Modern Header -->
    <header class="main-header" x-data="{ mobileMenuOpen: false }">
        <div class="header-content">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <span>Журавлик</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Главная</span>
                </a>

                <a href="{{ route('log.add') }}" class="nav-link {{ request()->routeIs('log.add') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Добавить запись</span>
                </a>

                <a href="{{ route('excel.store') }}" class="nav-link {{ request()->routeIs('excel.store') ? 'active' : '' }}">
                    <i class="fas fa-file-excel"></i>
                    <span>Отчеты</span>
                </a>

                <a href="{{ route('history.report') }}" class="nav-link {{ request()->routeIs('history.report') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>История отчетов</span>
                </a>

                <a href="{{ route('patient.flow') }}" class="nav-link {{ request()->routeIs('patient.flow') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Статистика</span>
                </a>

                <a href="{{ route('history') }}" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>История</span>
                </a>

                @if(auth()->user()->hasAccess('platform.index'))
                    <a href="{{ route('platform.main') }}" class="nav-link {{ request()->routeIs('platform.main') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>Админка</span>
                    </a>
                @endif
            </nav>

            <!-- User Menu -->
            <div class="user-menu">
                <button class="user-btn">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 0)) }}
                    </div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <div class="user-dropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-link">
                        <i class="fas fa-user"></i>
                        <span>Профиль</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Выйти</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="mobile-nav" :class="{ 'active': mobileMenuOpen }" @click.away="mobileMenuOpen = false">
            <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Главная</span>
            </a>

            <a href="{{ route('log.add') }}" class="mobile-nav-link {{ request()->routeIs('log.add') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i>
                <span>Добавить запись</span>
            </a>

            <a href="{{ route('excel.store') }}" class="mobile-nav-link {{ request()->routeIs('excel.store') ? 'active' : '' }}">
                <i class="fas fa-file-excel"></i>
                <span>Отчеты</span>
            </a>

            <a href="{{ route('history.report') }}" class="mobile-nav-link {{ request()->routeIs('history.report') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>История отчетов</span>
            </a>

            <a href="{{ route('patient.flow') }}" class="mobile-nav-link {{ request()->routeIs('patient.flow') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Статистика</span>
            </a>

            <a href="{{ route('history') }}" class="mobile-nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
                <i class="fas fa-clock-rotate-left"></i>
                <span>История</span>
            </a>

            @if(auth()->user()->hasAccess('platform.index'))
                <a href="{{ route('platform.main') }}" class="mobile-nav-link {{ request()->routeIs('platform.main') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>Админка</span>
                </a>
            @endif

            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                    <i class="fas fa-user"></i>
                    <span>Профиль</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Выйти</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        @isset($header)
            <div class="page-header">
                <h1 class="page-title">{{ $header }}</h1>
                <p class="page-subtitle">
                    @if(request()->routeIs('dashboard'))
                        Обзор системы учета пациентов
                    @elseif(request()->routeIs('log.add'))
                        Добавление новой записи пациента
                    @elseif(request()->routeIs('excel.store'))
                        Генерация и управление отчетами
                    @elseif(request()->routeIs('history.report'))
                        История созданных отчетов
                    @elseif(request()->routeIs('patient.flow'))
                        Статистика движения пациентов
                    @elseif(request()->routeIs('history'))
                        История изменений записей
                    @elseif(request()->routeIs('platform.main'))
                        Администрирование системы
                    @endif
                </p>
            </div>
        @endisset

        <!-- Content -->
        <div class="content-area">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-content">
            <div class="copyright">
                © {{ date('Y') }} Санаторий "Журавлик". Все права защищены.
            </div>
            <div class="footer-links">
                <a href="#" class="footer-link">Политика конфиденциальности</a>
                <a href="#" class="footer-link">Пользовательское соглашение</a>
                <a href="mailto:support@zhuravlik.ru" class="footer-link">Техническая поддержка</a>
            </div>
        </div>
    </footer>
</div>

<!-- Keep your existing JavaScript -->
<script>



    // Modal functionality (keep your existing modal code)
    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');
    const closeBtn = modal?.querySelector('.modal-close');

    function getScrollbarWidth() {
        return window.innerWidth - document.documentElement.clientWidth;
    }

    function openModal(title, bodyHTML) {
        if (!modal) return;

        modalTitle.textContent = title;
        modalBody.innerHTML = bodyHTML;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');

        const scrollBarWidth = getScrollbarWidth();
        if (scrollBarWidth > 0) {
            document.body.style.paddingRight = scrollBarWidth + 'px';
        }
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        if (!modal) return;

        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Initialize modal handlers if modal exists
    if (modal && closeBtn) {
        // Обработчик для кнопок открытия
        document.querySelectorAll('.open-modal-btn').forEach(button => {
            button.addEventListener('click', () => {
                if (button.hasAttribute('data-changes')) {
                    const changesJson = button.getAttribute('data-changes');
                    let tableHTML = '<div class="overflow-x-auto"><table class="w-full border-collapse border border-gray-200"><thead><tr>' +
                        '<th class="border border-gray-300 px-4 py-2 text-left bg-gray-50">Поле</th>' +
                        '<th class="border border-gray-300 px-4 py-2 text-left bg-gray-50">До</th>' +
                        '<th class="border border-gray-300 px-4 py-2 text-left bg-gray-50">После</th>' +
                        '</tr></thead><tbody>';

                    try {
                        const changes = JSON.parse(changesJson);
                        for (const [field, values] of Object.entries(changes)) {
                            tableHTML += `<tr>
                                <td class="border border-gray-300 px-4 py-2 font-medium">${field}</td>
                                <td class="border border-gray-300 px-4 py-2">${values.before ?? ''}</td>
                                <td class="border border-gray-300 px-4 py-2">${values.after ?? ''}</td>
                            </tr>`;
                        }
                    } catch (e) {
                        tableHTML += '<tr><td colspan="3" class="text-red-500 px-4 py-2">Ошибка парсинга данных изменений</td></tr>';
                    }
                    tableHTML += '</tbody></table></div>';

                    openModal('Изменения записи', tableHTML);

                } else {
                    const userId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-name') || '—';
                    const email = button.getAttribute('data-email') || '—';
                    const editUrl = button.getAttribute('data-edit-url');

                    const title = `Информация о сотруднике #${userId}`;
                    const bodyHTML = `
                        <div class="space-y-3">
                            <p><strong class="text-gray-700">ФИО:</strong> ${name}</p>
                            <p><strong class="text-gray-700">Email:</strong> ${email}</p>
                            <div class="pt-3">
                                <a href="${editUrl}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Перейти к редактированию профиля
                                </a>
                            </div>
                        </div>
                    `;

                    openModal(title, bodyHTML);
                }
            });
        });

        // Закрыть при клике на крестик
        closeBtn.addEventListener('click', closeModal);

        // Закрыть при клике вне модалки
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Закрыть по Esc
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    }

    // Autocomplete functions (keep your existing)
    function mkbAutocomplete(fetchUrl, nameInput, nameValue, initial, hidden) {
        return {
            query: initial,
            suggestions: [],
            highlightedIndex: -1,
            fetchSuggestions() {
                if (this.query.length < 2) {
                    this.suggestions = [];
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(fetchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        [nameInput]: this.query
                    }),
                })
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data;
                        this.highlightedIndex = -1;
                    })
                    .catch(() => {
                        this.suggestions = [];
                    });
            },
            highlightNext() {
                if (this.highlightedIndex < this.suggestions.length - 1) {
                    this.highlightedIndex++;
                }
            },
            highlightPrev() {
                if (this.highlightedIndex > 0) {
                    this.highlightedIndex--;
                }
            },
            selectHighlighted() {
                if (this.highlightedIndex >= 0) {
                    this.selectSuggestion(this.highlightedIndex);
                }
            },
            selectSuggestion(index) {
                this.query = `${this.suggestions[index].code}`;
                const hiddenInput = document.getElementById(nameValue);
                if (hiddenInput) {
                    hiddenInput.value = this.suggestions[index].value;
                }
                this.suggestions = [];
            }
        };
    }

    // Toastr notifications
    @if(session('toast'))
    $(document).ready(function() {
        toastr.success("{{ session('toast') }}", "Успешно", {
            timeOut: 5000,
            extendedTimeOut: 1000,
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: true,
            newestOnTop: true,
            showMethod: "slideDown",
            hideMethod: "slideUp",
            showDuration: 300,
            hideDuration: 300,
            showEasing: "swing",
            hideEasing: "linear",
            tapToDismiss: false,
            rtl: false
        });
    });
    @endif

    @if(session('toast-warn'))
    $(document).ready(function() {
        toastr.warning("{{ session('toast-warn') }}", "Внимание", {
            timeOut: 6000,
            extendedTimeOut: 1000,
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: true,
            newestOnTop: true,
            showMethod: "slideDown",
            hideMethod: "slideUp",
            showDuration: 300,
            hideDuration: 300,
            showEasing: "swing",
            hideEasing: "linear",
            tapToDismiss: false
        });
    });
    @endif

    // Инициализация Toastr с кастомными настройками
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "300",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "slideUp",
        "tapToDismiss": false,
        "rtl": false
    };

    function confirmDeletion(patientName) {
        return confirm(`Вы уверены, что хотите удалить запись пациента "${patientName}"? Это действие невозможно будет отменить.`);
    }
</script>
</body>
</html>
