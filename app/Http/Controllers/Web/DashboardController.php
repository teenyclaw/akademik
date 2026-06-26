<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $schoolId = session('school_id') ?? auth()->user()?->school_id;

        return view('dashboard.index', [
            'stats' => $this->dashboardService->getStats($schoolId),
            'gradeTrend' => $this->dashboardService->getGradeTrend($schoolId),
            'attendanceChart' => $this->dashboardService->getAttendanceChart($schoolId),
            'announcements' => $this->dashboardService->getRecentAnnouncements($schoolId),
        ]);
    }
}
