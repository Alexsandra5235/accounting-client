<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Система учета пациентов санатория «Журавлик»</title>

    <!-- Alpine.js (для навигации) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Если у вас настроен Vite с Tailwind -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Минимальный Tailwind для демонстрации -->
        <style>
            @layer base {
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                    line-height: 1.5;
                }
            }
            @layer components {
                .btn {
                    @apply inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150;
                }
                .nav-link {
                    @apply inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-gray-900 dark:text-gray-200 hover:border-gray-300;
                }
                .nav-link-active {
                    @apply border-indigo-500 text-gray-900 dark:text-gray-200;
                }
            }
            @layer utilities {
                .min-h-screen {
                    min-height: 100vh;
                }
                .bg-light {
                    background-color: #FDFDFC;
                }
                .dark\:bg-dark {
                    background-color: #0a0a0a;
                }
                .text-dark {
                    color: #1b1b18;
                }
                .dark\:text-light {
                    color: #FDFDFC;
                }
                .border-light {
                    border-color: #E5E7EB;
                }
                .dark\:border-dark {
                    border-color: #4B5563;
                }
            }
        </style>
    @endif
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-dark dark:text-light flex flex-col min-h-screen">

{{-- Навигационная шапка --}}
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-light dark:border-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Главная') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- Кнопка Войти / Личный кабинет --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @if (Route::has('login'))
                    @auth
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Личный кабинет') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link>
                                <input type="submit" value="Выйти">
                            </x-dropdown-link>
                        </form>
                    @else
                        <x-dropdown-link :href="route('login')">
                            {{ __('Войти') }}
                        </x-dropdown-link>
                    @endauth
                @endif
            </div>
            {{-- Для мобильных (можно дополнить, если нужна бургер-меню) --}}
        </div>
    </div>
</nav>

{{-- Основной контент --}}
<main class="flex-1 flex flex-col items-center justify-center max-w-5xl mx-auto sm:px-5 ">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
        {{-- Заголовок --}}
        <h1 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100 text-center">
            Система учета пациентов санатория «Журавлик»
        </h1>

        {{-- Описание --}}
        <h3 class="text-gray-700 dark:text-gray-300 mb-6 text-sm text-center">
            Добро пожаловать!
        </h3>
        <p class="text-gray-700 dark:text-gray-300 mb-6 text-sm text-center">
            Данная система предназначена для учёта пациентов нашего санатория.
            Регистрация возможна <strong>только</strong> через администратора (заведующую отделением).<br>
            Если у вас нет учётной записи — обратитесь к заведующей для получения доступа.
        </p>

        <h3 class="text-gray-700 dark:text-gray-300 mb-6 text-sm text-center">
            Что умеет данная система?
        </h3>

        {{-- Список преимуществ --}}
        <ul class="list-disc pl-5 mb-6 text-gray-700 dark:text-gray-300 text-sm">
            <li>История приёма пациентов</li>
            <li>Формирование отчётов о движении пациентов</li>
            <li>Просмотр статистики санатория</li>
        </ul>

        {{-- Кнопка Войти --}}
        <div class="flex justify-center">
            @if (Route::has('login'))
                @guest
                    <x-link-primary-button :href="route('login')" style="align-items: center">
                        {{ __('Войти') }}
                    </x-link-primary-button>
                @endguest
            @endif
        </div>
    </div>

    {{-- Нижний логотип или иллюстрация --}}
    <div class="mt-6">
        <svg class="w-32 h-32 text-gray-800 dark:text-gray-200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M50 150 C75 100, 125 100, 150 150" stroke="currentColor" stroke-width="6" fill="none" />
            <path d="M70 130 L100 90 L130 130" stroke="currentColor" stroke-width="6" fill="none" />
            <circle cx="100" cy="80" r="5" fill="currentColor" />
        </svg>
    </div>
</main>

{{-- Футер --}}
<footer class="bg-white dark:bg-gray-800 border-t border-light dark:border-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center">
        <p class="text-gray-700 dark:text-gray-300 text-sm">
            © {{ date('Y') }} Санаторий «Журавлик».
        </p>
        <div class="mt-2 sm:mt-0 space-x-4">
            <a href="https://zhuravlik.ru" target="_blank" class="text-gray-700 dark:text-gray-300 text-sm hover:underline">
                Официальный сайт санатория
            </a>
        </div>
    </div>
</footer>

</body>
</html>
