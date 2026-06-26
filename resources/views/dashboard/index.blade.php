<x-app-layout>
    <x-slot name="breadcrumb">
        <x-breadcrumb :items="[['label' => 'Dashboard']]" />
    </x-slot>

    <x-page-header
        title="Dashboard"
        description="Ringkasan data akademik sekolah hari ini."
    />

    {{-- Stat cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card title="Total Siswa" :value="number_format($stats['students'])">
            <x-slot:icon>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card title="Total Guru" :value="number_format($stats['teachers'])">
            <x-slot:icon>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card title="Total Kelas" :value="number_format($stats['classes'])">
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
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-gray-100">Tren Nilai Rata-rata (6 Bulan)</h3>
            <div class="relative h-72">
                <canvas id="gradeTrendChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-gray-100">Kehadiran Mingguan (Bulan Ini)</h3>
            <div class="relative h-72">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent announcements --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Pengumuman Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($announcements as $announcement)
                <div class="px-5 py-4 transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $announcement->title }}</p>
                            <p class="mt-1 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ Str::limit(strip_tags($announcement->content), 120) }}
                            </p>
                        </div>
                        <time class="shrink-0 text-xs text-gray-400 dark:text-gray-500">
                            {{ ($announcement->published_at ?? $announcement->created_at)?->translatedFormat('d M Y') }}
                        </time>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada pengumuman.
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(148, 163, 184, 0.2)' : 'rgba(203, 213, 225, 0.8)';
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
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            x: {
                                grid: { color: gridColor },
                                ticks: { color: textColor },
                            },
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { color: gridColor },
                                ticks: { color: textColor },
                            },
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
                                backgroundColor: '#4f46e5',
                                borderRadius: 6,
                            },
                            {
                                label: 'Tidak Hadir',
                                data: attendanceData.absent,
                                backgroundColor: isDark ? '#475569' : '#cbd5e1',
                                borderRadius: 6,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: { color: textColor },
                            },
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: { display: false },
                                ticks: { color: textColor },
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: { color: textColor },
                            },
                        },
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
