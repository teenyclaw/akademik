<?php

namespace App\Services;

use App\Domain\Enums\AttendanceStatus;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getStats(?int $schoolId): array
    {
        if (! $schoolId) {
            return [
                'students' => 0,
                'teachers' => 0,
                'classes' => 0,
                'attendance_percentage' => 0,
            ];
        }

        $today = today();

        $totalToday = Attendance::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->whereDate('date', $today)
            ->count();

        $presentToday = Attendance::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->whereDate('date', $today)
            ->where('status', AttendanceStatus::Present->value)
            ->count();

        return [
            'students' => Student::withoutGlobalScope('school')->where('school_id', $schoolId)->count(),
            'teachers' => Teacher::withoutGlobalScope('school')->where('school_id', $schoolId)->count(),
            'classes' => SchoolClass::withoutGlobalScope('school')->where('school_id', $schoolId)->count(),
            'attendance_percentage' => $totalToday > 0
                ? round(($presentToday / $totalToday) * 100, 1)
                : 0,
        ];
    }

    public function getGradeTrend(?int $schoolId): array
    {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = Carbon::parse($month)->translatedFormat('M Y');

            if (! $schoolId) {
                $values[] = 0;

                continue;
            }

            $average = Grade::withoutGlobalScope('school')
                ->where('school_id', $schoolId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->avg('score');

            $values[] = $average ? round((float) $average, 1) : 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    public function getAttendanceChart(?int $schoolId): array
    {
        $labels = [];
        $present = [];
        $absent = [];

        if (! $schoolId) {
            for ($week = 1; $week <= 4; $week++) {
                $labels[] = "Minggu {$week}";
                $present[] = 0;
                $absent[] = 0;
            }

            return compact('labels', 'present', 'absent');
        }

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $records = Attendance::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(fn (Attendance $attendance) => (int) ceil($attendance->date->day / 7));

        for ($week = 1; $week <= 4; $week++) {
            $labels[] = "Minggu {$week}";
            $weekRecords = $records->get($week, collect());

            $present[] = $weekRecords->where('status', AttendanceStatus::Present)->count();
            $absent[] = $weekRecords->whereIn('status', [
                AttendanceStatus::Absent,
                AttendanceStatus::Sick,
                AttendanceStatus::Permission,
            ])->count();
        }

        return compact('labels', 'present', 'absent');
    }

    public function getRecentAnnouncements(?int $schoolId, int $limit = 5): Collection
    {
        if (! $schoolId) {
            return collect();
        }

        return Announcement::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest('published_at')
            ->latest('created_at')
            ->limit($limit)
            ->get(['id', 'title', 'content', 'type', 'published_at', 'created_at']);
    }
}
