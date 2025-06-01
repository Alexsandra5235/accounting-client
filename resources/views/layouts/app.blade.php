<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

        <!-- jQuery (нужен для toastr) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        function mkbAutocomplete(fetchUrl, nameInput, nameValue) {
            return {
                query: '',
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

        function addressAutocomplete(fetchUrl, nameInput) {
            return {
                query: '',
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

        @if(session('toast'))
        $(document).ready(function() {
            toastr.success("{{ session('toast') }}", null, {
                timeOut: 5000,
                extendedTimeOut: 1000,
            });
        });
        @endif

        function confirmDeletion(patientName) {
            return confirm(`Вы уверены, что хотите удалить запись пациента "${patientName}"? Это действие невозможно будет отменить.`);
        }

        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('hidden');
        }

        window.onclick = function(event) {
            if (!event.target.matches('button')) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        }
    </script>
    </body>
</html>
