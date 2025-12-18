<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $statistics = $this->dashboardService->getStatistics();

        return view('admin.dashboard.index', compact('statistics'));
    }
}
