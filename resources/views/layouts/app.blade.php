<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'SIA Akademik') }}</title>

        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body
        class="h-full font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100"
        x-data="{
            sidebarOpen: false,
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            darkMode: localStorage.getItem('darkMode') === 'true',
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('darkMode', this.darkMode);
                document.documentElement.classList.toggle('dark', this.darkMode);
            },
            toggleSidebarCollapsed() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            }
        }"
        x-init="document.documentElement.classList.toggle('dark', darkMode)"
    >
        <div class="flex h-full min-h-screen">
            {{-- Mobile overlay --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-gray-900/60 lg:hidden"
                @click="sidebarOpen = false"
                style="display: none;"
            ></div>

            {{-- Sidebar --}}
            <aside
                :class="{
                    'translate-x-0': sidebarOpen,
                    '-translate-x-full': !sidebarOpen,
                    'lg:w-64': !sidebarCollapsed,
                    'lg:w-20': sidebarCollapsed
                }"
                class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-gray-200 bg-white transition-all duration-200 dark:border-gray-700 dark:bg-gray-800 lg:static lg:translate-x-0"
            >
                <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-4 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                        <x-application-logo class="h-8 w-8 shrink-0 fill-current text-indigo-600 dark:text-indigo-400" />
                        <span x-show="!sidebarCollapsed" x-cloak class="truncate text-lg font-bold text-indigo-600 dark:text-indigo-400">
                            SIA Akademik
                        </span>
                    </a>
                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 lg:hidden dark:text-gray-400 dark:hover:bg-gray-700"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4">
                    @include('layouts.navigation.sidebar')
                </nav>

                <div class="hidden border-t border-gray-200 p-3 dark:border-gray-700 lg:block">
                    <button
                        type="button"
                        @click="toggleSidebarCollapsed()"
                        class="flex w-full items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                        :title="sidebarCollapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'"
                    >
                        <svg class="h-5 w-5 transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </aside>

            {{-- Main content --}}
            <div class="flex min-w-0 flex-1 flex-col">
                {{-- Top navbar --}}
                <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden dark:text-gray-400 dark:hover:bg-gray-700"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="hidden min-w-0 sm:block">
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ auth()->user()->school?->name ?? config('app.name', 'SIA Akademik') }}
                            </p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                Sistem Informasi Akademik
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        {{-- Dark mode toggle --}}
                        <button
                            type="button"
                            @click="toggleDarkMode()"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                            :title="darkMode ? 'Mode terang' : 'Mode gelap'"
                        >
                            <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        {{-- User dropdown --}}
                        <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                    <svg class="hidden h-4 w-4 sm:inline" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">
                                    Profil
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Keluar
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                {{-- Breadcrumb & page heading --}}
                @if (isset($breadcrumb) || isset($header))
                    <div class="border-b border-gray-200 bg-white px-4 py-4 dark:border-gray-700 dark:bg-gray-800 sm:px-6 lg:px-8">
                        @isset($breadcrumb)
                            <div class="mb-2">{{ $breadcrumb }}</div>
                        @endisset
                        @isset($header)
                            <div>{{ $header }}</div>
                        @endisset
                    </div>
                @endif

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="px-4 pt-4 sm:px-6 lg:px-8">
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    </div>
                @endif
                @if (session('error'))
                    <div class="px-4 pt-4 sm:px-6 lg:px-8">
                        <x-alert type="error">{{ session('error') }}</x-alert>
                    </div>
                @endif

                {{-- Page content --}}
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
