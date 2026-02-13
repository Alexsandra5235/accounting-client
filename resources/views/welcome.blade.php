<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Система учета пациентов санатория «Журавлик»</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Vite (если есть) -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* ===== ОСНОВНЫЕ ПЕРЕМЕННЫЕ ===== */
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #10b981;
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
        }

        /* ===== СБРОС СТИЛЕЙ ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden; /* Убираем прокрутку */
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #f1f5f9 100%);
            color: var(--gray-800);
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            height: 100vh; /* На всю высоту экрана */
            overflow: hidden; /* Убираем прокрутку */
        }

        /* ===== ШАПКА ===== */
        .main-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            flex-shrink: 0; /* Не сжимается */
        }

        .header-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px; /* Уменьшенная высота шапки */
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--gray-900);
            font-weight: 700;
            font-size: 1.1rem; /* Чуть меньше */
        }

        .logo-icon {
            width: 32px; /* Уменьшили */
            height: 32px; /* Уменьшили */
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .desktop-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ===== КНОПКИ ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem; /* Уменьшили */
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 0.85rem; /* Уменьшили */
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .btn-outline-danger {
            background: transparent;
            border: 1px solid #ef4444;
            color: #ef4444;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            color: white;
        }

        /* ===== КОНТЕЙНЕР ===== */
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            height: calc(100vh - 100px); /* Вычитаем высоту шапки и футера */
            overflow: hidden;
        }

        /* ===== КАРТОЧКА ===== */
        .welcome-card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 440px; /* Чуть уже */
            margin: 0 auto;
        }

        .card-header {
            padding: 1.5rem 1.5rem 0.75rem 1.5rem; /* Уменьшили */
            background: linear-gradient(135deg, var(--gray-50), white);
            border-bottom: 1px solid var(--gray-100);
        }

        .card-header h1 {
            font-size: 1.5rem; /* Уменьшили */
            font-weight: 700;
            color: var(--gray-900);
            text-align: center;
            margin-bottom: 0.15rem;
        }

        .card-header h3 {
            font-size: 0.9rem; /* Уменьшили */
            font-weight: 400;
            color: var(--gray-600);
            text-align: center;
        }

        .card-body {
            padding: 1rem 1.5rem 1.5rem 1.5rem; /* Уменьшили */
        }

        /* ===== АВАТАР ===== */
        .avatar-circle {
            width: 60px; /* Уменьшили */
            height: 60px; /* Уменьшили */
            margin: 0 auto 0.75rem;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-circle i {
            color: var(--primary);
            font-size: 1.75rem; /* Уменьшили */
        }

        /* ===== ИНФОРМАЦИОННЫЙ БЛОК ===== */
        .info-box {
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
            border-left: 4px solid #f97316;
            border-radius: var(--radius);
            padding: 0.75rem; /* Уменьшили */
            margin: 0.75rem 0; /* Уменьшили */
            font-size: 0.85rem; /* Уменьшили */
            color: #7b341e;
        }

        .info-box i {
            color: #f97316;
            margin-right: 0.4rem;
        }

        .info-box p {
            margin-top: 0.4rem;
            font-size: 0.8rem; /* Уменьшили */
        }

        /* ===== СПИСОК ВОЗМОЖНОСТЕЙ ===== */
        .section-title {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem; /* Уменьшили */
            font-size: 0.95rem; /* Уменьшили */
        }

        .feature-list {
            list-style: none;
            margin: 0.5rem 0; /* Уменьшили */
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.4rem 0; /* Уменьшили */
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.85rem; /* Уменьшили */
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list i {
            width: 20px;
            color: var(--primary);
            font-size: 0.95rem;
        }

        /* ===== МОБИЛЬНОЕ МЕНЮ ===== */
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
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 1rem;
            position: absolute;
            top: 60px;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: var(--shadow-md);
        }

        .mobile-nav-item {
            padding: 0.6rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
            display: block;
            text-decoration: none;
            color: var(--gray-700);
            font-size: 0.9rem;
        }

        .mobile-logout-btn {
            width: 100%;
            text-align: left;
            padding: 0.6rem;
            background: #fee2e2;
            border-radius: var(--radius);
            border: none;
            color: #991b1b;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ===== ФУТЕР ===== */
        .main-footer {
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 0.75rem 1rem; /* Уменьшили */
            flex-shrink: 0; /* Не сжимается */
        }

        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--gray-500);
            font-size: 0.8rem; /* Уменьшили */
        }

        .footer-links {
            display: flex;
            gap: 1rem;
        }

        .footer-link {
            color: var(--gray-500);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: var(--primary);
        }

        /* ===== АНИМАЦИИ ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-card {
            animation: fadeIn 0.5s ease-out;
        }

        /* ===== УТИЛИТЫ ===== */
        .text-center { text-align: center; }
        .w-full { width: 100%; }

        /* ===== АДАПТИВНОСТЬ ===== */
        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
            }

            .footer-content {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .footer-links {
                justify-content: center;
            }

            .welcome-card {
                max-width: 400px;
            }

            .card-header h1 {
                font-size: 1.35rem;
            }
        }

        @media (max-width: 640px) {
            .mobile-menu-btn {
                display: block;
            }

            .desktop-menu {
                display: none;
            }

            .welcome-card {
                max-width: 100%;
            }

            .avatar-circle {
                width: 50px;
                height: 50px;
            }

            .avatar-circle i {
                font-size: 1.5rem;
            }

            .btn {
                padding: 0.35rem 0.7rem;
                font-size: 0.8rem;
            }
        }

        @media (max-height: 700px) {
            /* Для низких экранов */
            .card-header {
                padding: 1rem 1.5rem 0.5rem 1.5rem;
            }

            .avatar-circle {
                width: 50px;
                height: 50px;
                margin-bottom: 0.5rem;
            }

            .avatar-circle i {
                font-size: 1.5rem;
            }

            .feature-list li {
                padding: 0.3rem 0;
            }

            .card-body {
                padding: 0.75rem 1.5rem 1rem 1.5rem;
            }
        }

        /* x-cloak для Alpine.js */
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body>
<!-- Шапка -->
<header class="main-header" x-data="{ mobileMenuOpen: false }">
    <div class="header-content">
        <!-- Логотип -->
        <a href="{{ route('welcome') }}" class="logo">
            <div class="logo-icon">
                <i class="fas fa-stethoscope"></i>
            </div>
            <span>Журавлик</span>
        </a>

        <!-- Десктопное меню -->
        <nav class="desktop-menu">
            @auth
                <span style="color: var(--gray-600); font-size: 0.85rem;">
                        <i class="fas fa-user" style="margin-right: 0.25rem;"></i>{{ Auth::user()->name }}
                    </span>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline">
                    <i class="fas fa-user-circle"></i>
                    Личный кабинет
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        Выйти
                    </button>
                </form>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Войти
                    </a>
                @endif
            @endauth
        </nav>

        <!-- Кнопка мобильного меню -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Мобильное меню -->
    <div class="mobile-nav" x-show="mobileMenuOpen" x-cloak x-transition>
        @auth
            <div class="mobile-nav-item" style="background: var(--gray-100);">
                <i class="fas fa-user" style="color: var(--primary);"></i>
                {{ Auth::user()->name }}
            </div>
            <a href="{{ route('profile.edit') }}" class="mobile-nav-item">
                <i class="fas fa-user-circle" style="color: var(--primary);"></i>
                Личный кабинет
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Выйти
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="mobile-nav-item" style="background: var(--primary); color: white; text-align: center;">
                <i class="fas fa-sign-in-alt"></i>
                Войти
            </a>
        @endauth
    </div>
</header>

<!-- Основной контент -->
<div class="main-container">
    <div class="welcome-card">
        <div class="card-header">
            <h1>Система учета пациентов</h1>
            <h3>Санаторий «Журавлик»</h3>
        </div>

        <div class="card-body">
            <!-- Приветствие -->
            <div class="text-center">
                <div class="avatar-circle">
                    <i class="fas fa-hospital-user"></i>
                </div>
                <p style="color: var(--gray-600); font-size: 0.85rem;">
                    Добро пожаловать!
                </p>
            </div>

            <!-- Информационный блок -->
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <strong>Регистрация через администратора</strong>
                <p>
                    Обратитесь к заведующей для получения доступа.
                </p>
            </div>

            <!-- Возможности системы -->
            <h4 class="section-title">
                <i class="fas fa-star" style="color: #f59e0b; margin-right: 0.4rem;"></i>
                Возможности
            </h4>

            <ul class="feature-list">
                <li>
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>История приёма пациентов</span>
                </li>
                <li>
                    <i class="fas fa-file-excel"></i>
                    <span>Формирование отчётов</span>
                </li>
                <li>
                    <i class="fas fa-chart-line"></i>
                    <span>Статистика санатория</span>
                </li>
            </ul>

            <!-- Кнопка действия -->
            <div class="text-center" style="margin-top: 0.75rem;">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary w-full" style="justify-content: center;">
                        <i class="fas fa-sign-in-alt"></i>
                        Войти в систему
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary w-full" style="justify-content: center;">
                        <i class="fas fa-arrow-right"></i>
                        Перейти на сайт
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- Футер -->
<footer class="main-footer">
    <div class="footer-content">
        <div>
            <i class="fas fa-copyright"></i>
            {{ date('Y') }} Санаторий «Журавлик»
        </div>
        <div class="footer-links">
            <a href="https://zhuravlik.ru" target="_blank" class="footer-link">
                <i class="fas fa-external-link-alt"></i>
                Сайт
            </a>
            <a href="#" class="footer-link">
                <i class="fas fa-file-contract"></i>
                Политика
            </a>
        </div>
    </div>
</footer>
</body>
</html>
