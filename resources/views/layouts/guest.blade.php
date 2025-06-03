<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Вход</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 text-dark dark:text-light flex flex-col min-h-screen">
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
                            <x-nav-link :href="route('welcome')" :active="request()->routeIs('dashboard')">
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
                </div>
            </div>
        </nav>
        <div class="flex-1 flex flex-col items-center justify-center sm:justify-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
