<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Система учета пациентов санатория "Журавлик"</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- jQuery (нужен для Toastr) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
            /**, html,body{*/
            /*    padding:0;*/
            /*    margin:0;*/
            /*    box-sizing:border-box;*/
            /*    font-family:'Poppins', sans-serif;*/
            /*    perspective:800px;*/
            /*}*/
            /* Для всех scrollable-элементов */
            .timeline-wrapper::-webkit-scrollbar {
                width: 8px;
            }

            .timeline-wrapper::-webkit-scrollbar-track {
                background: #1e1e1e; /* цвет фона полосы прокрутки */
            }

            .timeline-wrapper::-webkit-scrollbar-thumb {
                background-color: #444;  /* цвет "ползунка" */
                border-radius: 4px;
            }
            .timeline-wrapper {
                max-height: 100vh; /* максимум на высоту экрана */
                overflow-y: auto;   /* вертикальная прокрутка */
                padding-right: 10px; /* чтобы скролл не перекрывал контент */
            }
            .timeline{
                width:800px;
                background-color:#072736;
                color:#fff;
                padding:30px 20px;
                box-shadow:0 0 10px rgba(0,0,0,.5);
            }
            .timeline ul{
                list-style-type:none;
                border-left:2px solid #094a68;
                padding:10px 5px;
            }
            .timeline ul li{
                padding:20px 20px;
                position:relative;
                cursor:pointer;
                transition:.5s;
            }
            .timeline ul li span{
                display:inline-block;
                background-color:#1685b8;
                border-radius:25px;
                padding:2px 5px;
                font-size:15px;
                text-align:center;
            }
            .timeline ul li .content h3{
                color:#34ace0;
                font-size:17px;
                padding-top:5px;
            }
            .timeline ul li .content p{
                padding:5px 0 15px 0;
                font-size:15px;
            }
            .timeline ul li:before{
                position:absolute;
                content:'';
                width:10px;
                height:10px;
                background-color:#34ace0;
                border-radius:50%;
                left:-11px;
                top:28px;
                transition:.5s;
            }
            .timeline ul li:hover{
                background-color:#071f2a;
            }
            .timeline ul li:hover:before{
                background-color:#0F0;
                box-shadow:0 0 10px 2px #0F0;
            }
            @media (max-width:300px){
                .timeline{
                    width:100%;
                    padding:30px 5px 30px 10px;
                }
                .timeline ul li .content h3{
                    color:#34ace0;
                    font-size:15px;
                }

            }

            /* Стили для модального окна */

            .modal-overlay {
                position: fixed;
                overflow: auto; /* фиксированное позиционирование относительно окна */
                top: 0; left: 0; right: 0; bottom: 0; /* растянуть на весь экран */
                display: flex;        /* включаем flexbox для центрирования */
                justify-content: center; /* по горизонтали центр */
                align-items: center;      /* по вертикали центр */
                background-color: rgba(0,0,0,0.6);
                opacity: 0;
                visibility: hidden;
                z-index: 9999;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .modal-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .modal-content {
                position: absolute;
                background: #fff;
                color: #000;
                border-radius: 8px;
                max-width: 500px;
                width: 90%;
                padding: 20px;
                box-shadow: 0 0 15px rgba(0,0,0,0.3);
                transform: translateY(-20px);
                transition: transform 0.3s ease;
            }
            .modal-overlay.active .modal-content {
                transform: translateY(0);
            }

            .modal-close {
                position: absolute;
                top: 10px;
                right: 15px;
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #333;
            }

            /* Убираем маркер у <summary> */
            details > summary {
                list-style: none;
                cursor: pointer;
                font-size: 20px;
                user-select: none;
            }
            /* Стили для выпадающего списка */
            details > ul {
                position: absolute;
                right: 0;
                margin-top: 0.25rem; /* чтобы меню чуть ниже кнопки */
                background: #2d3748; /* bg-gray-800 (тёмно-серый) */
                color: white;
                border: 1px solid #4a5568; /* border-gray-700 */
                border-radius: 0.375rem;    /* rounded-md */
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                padding: 0.25rem 0;         /* py-1 */
                min-width: 8rem;            /* примерно w-32 (128px) */
                z-index: 9999;
            }
            /* Элементы списка */
            details > ul li + li {
                border-top: 1px solid #4a5568; /* разделитель между пунктами */
            }
            details > ul li a,
            details > ul li form > input[type="submit"] {
                display: block;
                padding: 0.5rem 1rem;         /* px-4 py-2 */
                color: white;
                text-decoration: none;
            }
            /* Ховер-эффект для пунктов */
            details > ul li a:hover,
            details > ul li form > input[type="submit"]:hover {
                background: #4a5568; /* hover:bg-gray-700 */
            }

        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

    <script>

        @if(session('toast'))
            console.log('заходит е мае')
            $(document).ready(function() {
                toastr.success("{{ session('toast') }}", null, {
                    timeOut: 5000,
                    extendedTimeOut: 1000,
                });
            });
        @endif

        @if(session('toast-warn'))
        console.log('заходит е мае')
        $(document).ready(function() {
            toastr.warning("{{ session('toast-warn') }}", null, {
                timeOut: 5000,
                extendedTimeOut: 1000,
            });
        });
        @endif

        // Получаем элементы
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');
        const closeBtn = modal.querySelector('.modal-close');

        function getScrollbarWidth() {
            return window.innerWidth - document.documentElement.clientWidth;
        }

        function openModal(title, bodyHTML) {
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
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        // Обработчик для кнопок открытия
        document.querySelectorAll('.open-modal-btn').forEach(button => {
            button.addEventListener('click', () => {
                if (button.hasAttribute('data-changes')) {
                    const changesJson = button.getAttribute('data-changes');
                    let tableHTML = '<table style="width:100%; border-collapse: collapse;">' +
                        '<thead><tr>' +
                        '<th style="border: 1px solid #ccc; padding: 8px; text-align:left;">Поле</th>' +
                        '<th style="border: 1px solid #ccc; padding: 8px; text-align:left;">До</th>' +
                        '<th style="border: 1px solid #ccc; padding: 8px; text-align:left;">После</th>' +
                        '</tr></thead><tbody>';

                    try {
                        console.log(changesJson);
                        const changes = JSON.parse(changesJson);
                        for (const [field, values] of Object.entries(changes)) {
                            tableHTML += `<tr>
                        <td style="border: 1px solid #ccc; padding: 8px;">${field}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">${values.before ?? ''}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">${values.after ?? ''}</td>
                    </tr>`;
                        }
                    } catch (e) {
                        tableHTML += '<tr><td colspan="3" style="color: red; padding: 8px;">Ошибка парсинга данных изменений</td></tr>';
                    }
                    tableHTML += '</tbody></table>';

                    openModal('Изменения записи', tableHTML);

                } else {
                    const userId = button.getAttribute('data-user-id');
                    const name = button.getAttribute('data-name') || '—';
                    const email = button.getAttribute('data-email') || '—';
                    const editUrl = button.getAttribute('data-edit-url');

                    const title = `Информация о сотруднике #${userId}`;
                    const bodyHTML = `
            <p><strong>ФИО:</strong> ${name}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p style="margin-top: 1rem;">
                <x-link-primary-button href="${editUrl}" target="_blank">
                    Перейти к редактированию профиля
                </x-link-primary-button>
            </p>
        `;

                    openModal(title, bodyHTML);
                }
            });
        });

        // Закрыть при клике на крестик
        closeBtn.addEventListener('click', closeModal);

        // Закрыть при клике вне модалки (на overlay)
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
                    hiddenInput.value = this.suggestions[index].value
                    this.suggestions = [];
                }
            };
        }

        function addressAutocomplete(fetchUrl, nameInput, initial) {
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
                    this.query = `${this.suggestions[index].value}`;
                    this.suggestions = [];
                }
            };
        }

        function confirmDeletion(patientName) {
            return confirm(`Вы уверены, что хотите удалить запись пациента "${patientName}"? Это действие невозможно будет отменить.`);
        }

        function dropdownComponent() {
            return {
                open: false,
                // объект с inline-стилями: left/right/top/bottom
                menuPosition: {
                    left: 'auto',
                    right: '0px',
                    top: '100%',
                    bottom: 'auto',
                },

                toggle(event) {
                    // Если уже открыто — просто закрываем
                    if (this.open) {
                        this.close();
                        return;
                    }

                    // Ждём следующего запуска рендера Alpine, чтобы можно было измерить размеры
                    this.$nextTick(() => {
                        const buttonRect = event.currentTarget.getBoundingClientRect();
                        const menuEl = this.$refs.menu;
                        const menuHeight = menuEl.offsetHeight;
                        const menuWidth = menuEl.offsetWidth;
                        const viewportWidth = window.innerWidth;
                        const viewportHeight = window.innerHeight;

                        // Считаем пространство снизу кнопки до низа экрана
                        const spaceBelow = viewportHeight - buttonRect.bottom;
                        const spaceAbove = buttonRect.top;

                        // Если места снизу недостаточно (меню «не влезает» полностью), показываем вверх
                        if (spaceBelow < menuHeight && spaceAbove >= menuHeight) {
                            // «приклеиваем» меню к нижней границе кнопки сверху
                            this.menuPosition.top = 'auto';
                            this.menuPosition.bottom = '100%';
                        } else {
                            // Иначе стандартно показываем снизу
                            this.menuPosition.top = '100%';
                            this.menuPosition.bottom = 'auto';
                        }

                        // Горизонтальное позиционирование: сначала пробуем right: 0
                        if (buttonRect.right + menuWidth > viewportWidth) {
                            // не влезает справа → показываем влево
                            this.menuPosition.right = 'auto';
                            this.menuPosition.left = '0px';
                        } else {
                            // влезает справа
                            this.menuPosition.left = 'auto';
                            this.menuPosition.right = '0px';
                        }

                        // Открываем меню
                        this.open = true;
                    });
                },

                close() {
                    this.open = false;
                }
            }
        }

    </script>
    </body>
</html>
