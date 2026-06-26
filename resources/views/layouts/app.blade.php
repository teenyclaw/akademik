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
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body
        class="h-full font-sans antialiased bg-surface-muted text-slate-900 dark:bg-slate-950 dark:text-slate-100"
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
        <div class="flex h-full min-h-screen bg-mesh-light dark:bg-mesh-dark">
            {{-- Mobile overlay --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-slate-900/70 backdrop-blur-sm lg:hidden"
                @click="sidebarOpen = false"
                style="display: none;"
            ></div>

            {{-- Sidebar --}}
            <aside
                :class="{
                    'translate-x-0': sidebarOpen,
                    '-translate-x-full': !sidebarOpen,
                    'lg:w-72': !sidebarCollapsed,
                    'lg:w-[5.25rem]': sidebarCollapsed
                }"
                class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-sidebar-gradient shadow-sidebar transition-all duration-300 lg:static lg:translate-x-0"
            >
                {{-- Logo --}}
                <div class="flex h-[4.25rem] shrink-0 items-center justify-between border-b border-white/10 px-5">
                    <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-gradient shadow-premium-glow">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div x-show="!sidebarCollapsed" x-cloak class="min-w-0">
                            <p class="truncate text-base font-bold text-white">SIA Akademik</p>
                            <p class="truncate text-[11px] font-medium text-indigo-200/70">Multi-Instansi</p>
                        </div>
                    </a>
                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="rounded-lg p-1.5 text-indigo-200/70 hover:bg-white/10 lg:hidden"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-5">
                    @include('layouts.navigation.sidebar')
                </nav>

                {{-- Sidebar footer --}}
                <div class="hidden border-t border-white/10 p-3 lg:block">
                    <button
                        type="button"
                        @click="toggleSidebarCollapsed()"
                        class="flex w-full items-center justify-center rounded-xl p-2.5 text-indigo-200/70 transition hover:bg-white/10"
                        :title="sidebarCollapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'"
                    >
                        <svg class="h-5 w-5 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </aside>

            {{-- Main content --}}
            <div class="flex min-w-0 flex-1 flex-col">
                {{-- Top navbar --}}
                <header class="glass-header sticky top-0 z-30 flex h-[4.25rem] shrink-0 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="rounded-xl p-2.5 text-slate-500 transition hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="hidden min-w-0 sm:block">
                            <p class="truncate text-sm font-bold text-slate-900 dark:text-white">
                                {{ auth()->user()->school?->name ?? config('app.name', 'SIA Akademik') }}
                            </p>
                            <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                Sistem Informasi Akademik
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <button
                            type="button"
                            @click="toggleDarkMode()"
                            class="rounded-xl p-2.5 text-slate-500 transition hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                            :title="darkMode ? 'Mode terang' : 'Mode gelap'"
                        >
                            <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-slate-800 rounded-xl shadow-premium-lg border border-slate-200/80 dark:border-slate-700">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2.5 rounded-xl border border-slate-200/80 bg-white py-1.5 pl-1.5 pr-3 text-sm font-medium shadow-sm transition hover:border-slate-300 hover:shadow dark:border-slate-700 dark:bg-slate-800 dark:hover:border-slate-600">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-gradient text-sm font-bold text-white shadow-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    <span class="hidden max-w-[120px] truncate text-slate-700 dark:text-slate-200 sm:inline">{{ auth()->user()->name }}</span>
                                    <svg class="hidden h-4 w-4 text-slate-400 sm:inline" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="border-b border-slate-100 px-4 py-3 dark:border-slate-700">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
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

                @if (isset($breadcrumb) || isset($header))
                    <div class="border-b border-slate-200/60 bg-white/50 px-4 py-4 backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/50 sm:px-6 lg:px-8">
                        @isset($breadcrumb)
                            <div class="mb-2">{{ $breadcrumb }}</div>
                        @endisset
                        @isset($header)
                            <div>{{ $header }}</div>
                        @endisset
                    </div>
                @endif

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

                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
