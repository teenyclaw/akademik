<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIA Akademik') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen">
            {{-- Left panel — branding --}}
            <div class="relative hidden w-1/2 overflow-hidden bg-sidebar-gradient lg:flex lg:flex-col lg:justify-between lg:p-12">
                <div class="absolute inset-0 bg-mesh-dark opacity-60"></div>
                <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-brand-500/20 blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-violet-500/20 blur-3xl"></div>

                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-gradient shadow-premium-glow">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-white">SIA Akademik</p>
                            <p class="text-sm text-indigo-200/70">Sistem Informasi Akademik</p>
                        </div>
                    </div>
                </div>

                <div class="relative space-y-6">
                    <h2 class="text-3xl font-bold leading-tight text-white">
                        Kelola akademik<br>
                        <span class="text-indigo-300">multi-instansi</span> dengan mudah.
                    </h2>
                    <p class="max-w-md text-base leading-relaxed text-indigo-100/70">
                        Platform terintegrasi untuk sekolah, guru, siswa, dan orang tua — absensi, nilai, rapor, pembayaran, dan e-learning dalam satu sistem.
                    </p>
                    <div class="flex gap-6 pt-2">
                        <div>
                            <p class="text-2xl font-bold text-white">28+</p>
                            <p class="text-xs text-indigo-200/60">Modul</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">8</p>
                            <p class="text-xs text-indigo-200/60">Peran Pengguna</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">Multi</p>
                            <p class="text-xs text-indigo-200/60">Tenant</p>
                        </div>
                    </div>
                </div>

                <p class="relative text-xs text-indigo-200/40">&copy; {{ date('Y') }} SIA Akademik. All rights reserved.</p>
            </div>

            {{-- Right panel — form --}}
            <div class="flex w-full flex-col items-center justify-center bg-surface-muted px-6 py-12 lg:w-1/2">
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-gradient shadow-premium-glow">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <p class="text-lg font-bold text-slate-900">SIA Akademik</p>
                </div>

                <div class="w-full max-w-md">
                    <div class="premium-card p-8 shadow-premium-lg">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
