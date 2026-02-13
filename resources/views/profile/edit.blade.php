<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- КАРТОЧКА 1: Информация профиля -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-id-card text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Информация профиля</h3>
                            <h2 class="text-lg font-semibold text-gray-900">Дата регистрации {{ $user->created_at ? $user->created_at->format('d.m.Y') : '---' }}</h2>
                            <p class="text-sm text-gray-600 mt-0.5">
                                Обновите информацию о вашем аккаунте
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Форма для подтверждения email -->
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Поле Имя -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user text-blue-600 mr-1"></i>
                                Имя пользователя
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   required
                                   autofocus
                                   autocomplete="name">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Поле Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-envelope text-blue-600 mr-1"></i>
                                Электронная почта
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   required
                                   autocomplete="username">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-3 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                                    <p class="text-sm text-yellow-800">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>
                                        {{ __('Ваш адрес электронной почты не подтвержден.') }}

                                        <button form="send-verification"
                                                class="underline text-sm text-yellow-700 hover:text-yellow-900 font-medium ml-1">
                                            {{ __('Отправить повторно') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-sm font-medium text-green-600 flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ __('Новая ссылка для подтверждения была отправлена на ваш адрес электронной почты.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Кнопка сохранения -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Сохранить изменения
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }"
                                   x-show="show"
                                   x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-green-600 flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ __('Сохранено.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- КАРТОЧКА 2: Обновление пароля -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-lock text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Обновление пароля</h3>
                            <p class="text-sm text-gray-600 mt-0.5">
                                Чтобы обеспечить безопасность вашей учётной записи, используйте сложный пароль
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <!-- Текущий пароль -->
                        <div>
                            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-key text-gray-600 mr-1"></i>
                                Текущий пароль
                            </label>
                            <input type="password"
                                   id="update_password_current_password"
                                   name="current_password"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Новый пароль -->
                        <div>
                            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-lock text-green-600 mr-1"></i>
                                Новый пароль
                            </label>
                            <input type="password"
                                   id="update_password_password"
                                   name="password"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   autocomplete="new-password">

                            <!-- Подсказка по сложности пароля -->
                            <div class="mt-2 text-xs text-gray-500 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                <span>Минимум 8 символов, хотя бы одна буква и цифра</span>
                            </div>

                            @error('password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Подтверждение пароля -->
                        <div>
                            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                Подтвердите новый пароль
                            </label>
                            <input type="password"
                                   id="update_password_password_confirmation"
                                   name="password_confirmation"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Кнопка сохранения -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                            <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700 focus:ring-green-500">
                                <i class="fas fa-save mr-2"></i>
                                Обновить пароль
                            </button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }"
                                   x-show="show"
                                   x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-green-600 flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ __('Пароль обновлен.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- КАРТОЧКА 3: Дополнительная информация (опционально) -->
            <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Безопасность аккаунта</h4>
                            <p class="text-sm text-gray-600">Рекомендуется регулярно обновлять пароль для защиты ваших данных</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-check mr-1"></i>
                                    Аккаунт защищен
                                </span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-clock mr-1"></i>
                                    Последний вход: {{ now()->format('d.m.Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<!-- Стили для карточек (если не определены глобально) -->
<style>
    .stat-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
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
        color: #3b82f6;
    }

    .stat-icon.green {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.2));
        color: #10b981;
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.2));
        color: #8b5cf6;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, #f9fafb, white);
    }

    .card-body {
        padding: 1.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        transform: translateY(-1px);
    }

    /* Анимации */
    [x-cloak] {
        display: none !important;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    [x-show="true"] {
        animation: slideIn 0.3s ease-out;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .grid-cols-1.md\:grid-cols-3 {
            grid-template-columns: 1fr;
        }

        .card-header {
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }
    }
</style>
