<x-app-layout>
    <x-slot name="breadcrumb">
        <x-breadcrumb :items="[['label' => 'Dashboard']]" />
    </x-slot>

    {{-- Welcome banner --}}
    <div class="relative mb-8 overflow-hidden rounded-2xl bg-brand-gradient p-6 shadow-premium-glow sm:p-8">
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/5 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-100">Halo, {{ auth()->user()->name }} 👋</p>
                <h1 class="mt-1 text-2xl font-bold text-white sm:text-3xl">
                    {{ auth()->user()->school?->name ?? 'Dashboard Akademik' }}
                </h1>
                <p class="mt-2 text-sm text-indigo-100/80">Ringkasan data akademik sekolah hari ini.</p>
            </div>
            <div class="flex shrink-0 items-center gap-3 rounded-xl bg-white/15 px-5 py-3 ring-1 ring-white/20 backdrop-blur-sm">
                <svg class="h-8 w-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div>
                    <p class="text-xs font-medium text-indigo-200/70">Hari ini</p>
                    <p class="text-sm font-bold text-white">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card title="Total Siswa" :value="number_format($stats['students'])" color="indigo">
            <x-slot:icon>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card title="Total Guru" :value="number_format($stats['teachers'])" color="violet">
            <x-slot:icon>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card title="Total Kelas" :value="number_format($stats['classes'])" color="blue">
            <x-slot:icon>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Kehadiran Hari Ini"
            :value="$stats['attendance_percentage'].'%'"
            :trend="$stats['attendance_percentage'] >= 80 ? 'Tingkat kehadiran baik' : 'Perlu perhatian'"
            :trend-up="$stats['attendance_percentage'] >= 80"
            color="emerald"
        >
            <x-slot:icon>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Charts --}}
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="premium-card p-6">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Tren Nilai Rata-rata</h3>
                <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-600 dark:bg-brand-900/30 dark:text-brand-400">6 Bulan</span>
            </div>
            <div class="relative h-72">
                <canvas id="gradeTrendChart"></canvas>
            </div>
        </div>

        <div class="premium-card p-6">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Kehadiran Mingguan</h3>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">Bulan Ini</span>
            </div>
            <div class="relative h-72">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent announcements --}}
    <div class="premium-card overflow-hidden">
        <div class="premium-card-header flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Pengumuman Terbaru</h3>
            @can('announcements.view')
                <a href="{{ route('announcements.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700 dark:text-brand-400">Lihat semua →</a>
            @endcan
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
            @forelse ($announcements as $announcement)
                <div class="px-6 py-4 transition hover:bg-slate-50/80 dark:hover:bg-slate-700/20">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $announcement->title }}</p>
                            <p class="mt-1 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ Str::limit(strip_tags($announcement->content), 120) }}
                            </p>
                        </div>
                        <time class="shrink-0 rounded-lg bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                            {{ ($announcement->published_at ?? $announcement->created_at)?->translatedFormat('d M Y') }}
                        </time>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Belum ada pengumuman.</p>
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(148, 163, 184, 0.15)' : 'rgba(203, 213, 225, 0.6)';
                const textColor = isDark ? '#94a3b8' : '#64748b';

                const gradeTrendData = @json($gradeTrend);
                const attendanceData = @json($attendanceChart);

                new Chart(document.getElementById('gradeTrendChart'), {
                    type: 'line',
                    data: {
                        labels: gradeTrendData.labels,
                        datasets: [{
                            label: 'Rata-rata Nilai',
                            data: gradeTrendData.values,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.08)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: textColor, font: { size: 11 } } },
                            y: { beginAtZero: true, max: 100, grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 } } },
                        },
                    },
                });

                new Chart(document.getElementById('attendanceChart'), {
                    type: 'bar',
                    data: {
                        labels: attendanceData.labels,
                        datasets: [
                            {
                                label: 'Hadir',
                                data: attendanceData.present,
                                backgroundColor: '#6366f1',
                                borderRadius: 8,
                                borderSkipped: false,
                            },
                            {
                                label: 'Tidak Hadir',
                                data: attendanceData.absent,
                                backgroundColor: isDark ? '#334155' : '#e2e8f0',
                                borderRadius: 8,
                                borderSkipped: false,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { labels: { color: textColor, usePointStyle: true, pointStyle: 'circle' } } },
                        scales: {
                            x: { stacked: true, grid: { display: false }, ticks: { color: textColor, font: { size: 11 } } },
                            y: { stacked: true, beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 } } },
                        },
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
