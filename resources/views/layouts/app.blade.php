<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="toast-success" content="{{ session('toast') ?? '' }}">
    <meta name="toast-warn" content="{{ session('toast-warn') ?? '' }}">

    <title>Система учета пациентов санатория "Журавлик"</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Scripts -->
    @vite([
        'resources/css/app.css',
        'resources/css/layout.css',
        'resources/js/app.js',
        'resources/js/layout.js',
    ])
    @stack('styles')
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
            <nav class="nav-menu js-overflow-menu">
                <a href="{{ route('dashboard') }}" class="nav-link js-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Главная</span>
                </a>

                <a href="{{ route('log.add') }}" class="nav-link js-nav-item {{ request()->routeIs('log.add') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Добавить запись</span>
                </a>

                <a href="{{ route('excel.store') }}" class="nav-link js-nav-item {{ request()->routeIs('excel.store') ? 'active' : '' }}">
                    <i class="fas fa-file-excel"></i>
                    <span>Отчеты</span>
                </a>

                <a href="{{ route('history.report') }}" class="nav-link js-nav-item {{ request()->routeIs('history.report') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>История отчетов</span>
                </a>

                <a href="{{ route('patient.flow') }}" class="nav-link js-nav-item {{ request()->routeIs('patient.flow') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Статистика</span>
                </a>

                <a href="{{ route('history') }}" class="nav-link js-nav-item {{ request()->routeIs('history') ? 'active' : '' }}">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>История</span>
                </a>

                @if(auth()->user()->hasAccess('platform.index'))
                    <a href="{{ route('platform.main') }}" class="nav-link js-nav-item {{ request()->routeIs('platform.main') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>Админка</span>
                    </a>
                @endif

                <div class="more-menu hidden js-more-menu">
                    <button type="button" class="nav-link js-more-toggle">
                        <i class="fas fa-ellipsis-h"></i>
                        <span>Еще</span>
                    </button>
                    <div class="more-dropdown js-more-dropdown"></div>
                </div>
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
                    <button type="submit" class="dropdown-link dropdown-btn-reset">
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

            <div class="mobile-nav-extra">
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                    <i class="fas fa-user"></i>
                    <span>Профиль</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link dropdown-btn-reset">
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

@stack('scripts')
</body>
</html>
