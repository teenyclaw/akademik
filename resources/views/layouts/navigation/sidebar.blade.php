@php
    $routeUrl = fn (string $name) => Route::has($name) ? route($name) : '#';

    $masterRoutes = [
        'grade-levels.*',
        'majors.*',
        'classes.*',
        'rooms.*',
        'lesson-hours.*',
    ];

    $masterActive = request()->routeIs(...$masterRoutes);
@endphp

<ul class="space-y-1">
    @can('dashboard.view')
        <li>
            <x-sidebar-link
                :href="$routeUrl('dashboard')"
                :active="request()->routeIs('dashboard')"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </x-slot:icon>
                Dashboard
            </x-sidebar-link>
        </li>
    @endcan

    @can('academic-years.view')
        <li>
            <x-sidebar-link
                :href="$routeUrl('academic-years.index')"
                :active="request()->routeIs('academic-years.*')"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </x-slot:icon>
                Tahun Ajaran
            </x-sidebar-link>
        </li>
    @endcan

    @canany(['grade-levels.view', 'majors.view', 'classes.view', 'rooms.view', 'lesson-hours.view'])
        <li x-data="{ open: {{ $masterActive ? 'true' : 'false' }} }">
            <button
                type="button"
                @click="open = !open"
                class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-indigo-100/70 transition hover:bg-white/10 hover:text-white"
            >
                <span class="flex h-5 w-5 shrink-0 items-center justify-center text-indigo-300/60 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </span>
                <span x-show="!sidebarCollapsed" x-cloak class="flex-1 truncate text-left">Data Master</span>
                <svg x-show="!sidebarCollapsed" x-cloak class="h-4 w-4 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <ul x-show="open && !sidebarCollapsed" x-cloak class="ms-5 mt-1 space-y-1 border-l-2 border-white/10 pl-3">
                @can('grade-levels.view')
                    <li>
                        <x-sidebar-link
                            :href="$routeUrl('grade-levels.index')"
                            :active="request()->routeIs('grade-levels.*')"
                            class="py-2 text-xs"
                        >
                            Tingkat Kelas
                        </x-sidebar-link>
                    </li>
                @endcan
                @can('majors.view')
                    <li>
                        <x-sidebar-link
                            :href="$routeUrl('majors.index')"
                            :active="request()->routeIs('majors.*')"
                            class="py-2 text-xs"
                        >
                            Jurusan
                        </x-sidebar-link>
                    </li>
                @endcan
                @can('classes.view')
                    <li>
                        <x-sidebar-link
                            :href="$routeUrl('classes.index')"
                            :active="request()->routeIs('classes.*')"
                            class="py-2 text-xs"
                        >
                            Kelas
                        </x-sidebar-link>
                    </li>
                @endcan
                @can('rooms.view')
                    <li>
                        <x-sidebar-link
                            :href="$routeUrl('rooms.index')"
                            :active="request()->routeIs('rooms.*')"
                            class="py-2 text-xs"
                        >
                            Ruangan
                        </x-sidebar-link>
                    </li>
                @endcan
                @can('lesson-hours.view')
                    <li>
                        <x-sidebar-link
                            :href="$routeUrl('lesson-hours.index')"
                            :active="request()->routeIs('lesson-hours.*')"
                            class="py-2 text-xs"
                        >
                            Jam Pelajaran
                        </x-sidebar-link>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @can('students.view')
        <li>
            <x-sidebar-link :href="$routeUrl('students.index')" :active="request()->routeIs('students.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </x-slot:icon>
                Siswa
            </x-sidebar-link>
        </li>
    @endcan

    @can('teachers.view')
        <li>
            <x-sidebar-link :href="$routeUrl('teachers.index')" :active="request()->routeIs('teachers.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </x-slot:icon>
                Guru
            </x-sidebar-link>
        </li>
    @endcan

    @can('parents.view')
        <li>
            <x-sidebar-link :href="$routeUrl('parents.index')" :active="request()->routeIs('parents.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </x-slot:icon>
                Orang Tua
            </x-sidebar-link>
        </li>
    @endcan

    @can('subjects.view')
        <li>
            <x-sidebar-link :href="$routeUrl('subjects.index')" :active="request()->routeIs('subjects.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </x-slot:icon>
                Mata Pelajaran
            </x-sidebar-link>
        </li>
    @endcan

    @can('schedules.view')
        <li>
            <x-sidebar-link :href="$routeUrl('schedules.index')" :active="request()->routeIs('schedules.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:icon>
                Jadwal
            </x-sidebar-link>
        </li>
    @endcan

    @can('attendances.view')
        <li>
            <x-sidebar-link :href="$routeUrl('attendances.index')" :active="request()->routeIs('attendances.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </x-slot:icon>
                Absensi
            </x-sidebar-link>
        </li>
    @endcan

    @can('grades.view')
        <li>
            <x-sidebar-link :href="$routeUrl('grades.index')" :active="request()->routeIs('grades.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </x-slot:icon>
                Penilaian
            </x-sidebar-link>
        </li>
    @endcan

    @can('report-cards.view')
        <li>
            <x-sidebar-link :href="$routeUrl('report-cards.index')" :active="request()->routeIs('report-cards.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
                Rapor
            </x-sidebar-link>
        </li>
    @endcan

    @can('announcements.view')
        <li>
            <x-sidebar-link :href="$routeUrl('announcements.index')" :active="request()->routeIs('announcements.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </x-slot:icon>
                Pengumuman
            </x-sidebar-link>
        </li>
    @endcan

    @can('payments.view')
        <li>
            <x-sidebar-link :href="$routeUrl('payments.index')" :active="request()->routeIs('payments.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </x-slot:icon>
                Pembayaran
            </x-sidebar-link>
        </li>
    @endcan

    @can('elearning.view')
        <li>
            <x-sidebar-link :href="$routeUrl('elearning.index')" :active="request()->routeIs('elearning.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </x-slot:icon>
                E-Learning
            </x-sidebar-link>
        </li>
    @endcan

    @can('reports.view')
        <li>
            <x-sidebar-link :href="$routeUrl('reports.index')" :active="request()->routeIs('reports.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </x-slot:icon>
                Laporan
            </x-sidebar-link>
        </li>
    @endcan

    @can('school-settings.view')
        <li>
            <x-sidebar-link :href="$routeUrl('school-settings.index')" :active="request()->routeIs('school-settings.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </x-slot:icon>
                Pengaturan
            </x-sidebar-link>
        </li>
    @endcan

    @can('schools.view')
        <li class="mt-4 border-t border-gray-200 pt-4 dark:border-gray-700">
            <p x-show="!sidebarCollapsed" x-cloak class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-indigo-300/40">
                Admin
            </p>
            <x-sidebar-link :href="$routeUrl('schools.index')" :active="request()->routeIs('schools.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </x-slot:icon>
                Sekolah
            </x-sidebar-link>
        </li>
    @endcan
</ul>
