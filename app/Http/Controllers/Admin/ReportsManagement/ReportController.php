<?php

namespace App\Http\Controllers\Admin\ReportsManagement;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Province;
use App\Services\Admin\ReportsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportsService $reportsService
    ) {}

    /**
     * Display reports index page.
     */
    public function index(): View
    {
        $summary = $this->reportsService->getSummaryReport();

        return view('admin.reports-management.index', compact('summary'));
    }

    /**
     * Display donation report.
     */
    public function donations(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'blood_type', 'status']);
        $report = $this->reportsService->getDonationReport($filters);

        return view('admin.reports-management.donations', $report);
    }

    /**
     * Display blood request report.
     */
    public function bloodRequests(Request $request): View
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'blood_type']);
        $report = $this->reportsService->getBloodRequestReport($filters);

        return view('admin.reports-management.blood-requests', $report);
    }

    /**
     * Display inventory report.
     */
    public function inventory(Request $request): View
    {
        $filters = $request->only(['status', 'blood_type', 'expiration_filter']);
        $report = $this->reportsService->getInventoryReport($filters);

        return view('admin.reports-management.inventory', $report);
    }

    /**
     * Display user statistics report.
     */
    public function userStatistics(): View
    {
        $report = $this->reportsService->getUserStatisticsReport();

        return view('admin.reports-management.user-statistics', $report);
    }

    /**
     * Display summary report.
     */
    public function summary(): View
    {
        $report = $this->reportsService->getSummaryReport();

        return view('admin.reports-management.summary', $report);
    }

    /**
     * Display active donors report.
     */
    public function activeDonors(Request $request): View
    {
        $filters = $request->only(['province_id', 'blood_type', 'date_from', 'date_to']);
        $report = $this->reportsService->getActiveDonorsReport($filters);
        $provinces = Province::all();

        return view('admin.reports-management.active-donors', array_merge($report, ['provinces' => $provinces]));
    }

    /**
     * Display blood shortage report.
     */
    public function shortage(Request $request): View
    {
        $filters = $request->only(['blood_type', 'threshold']);
        $report = $this->reportsService->getBloodShortageReport($filters);

        return view('admin.reports-management.shortage', $report);
    }

    /**
     * Display approved requests report.
     */
    public function approvedRequests(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'province_id', 'blood_type']);
        $report = $this->reportsService->getApprovedRequestsReport($filters);
        $provinces = Province::all();

        return view('admin.reports-management.approved-requests', array_merge($report, ['provinces' => $provinces]));
    }

    /**
     * Display donation history report.
     */
    public function donationHistory(Request $request): View
    {
        $filters = $request->only(['donor_id', 'date_from', 'date_to']);
        $report = $this->reportsService->getDonationHistoryByDonor($filters);
        $donors = Donor::with('user')->get();

        return view('admin.reports-management.donation-history', array_merge($report, ['allDonors' => $donors]));
    }

    /**
     * Display reports by province.
     */
    public function byProvince(Request $request): View
    {
        $provinceId = $request->get('province_id');
        $filters = $request->only(['blood_type', 'status', 'date_from', 'date_to']);

        if ($provinceId) {
            $report = $this->reportsService->getReportsByProvince($provinceId, $filters);
        } else {
            $report = ['province' => null];
        }

        $provinces = Province::all();

        return view('admin.reports-management.by-province', array_merge($report, ['provinces' => $provinces, 'filters' => $filters]));
    }

    /**
     * Display monthly/yearly report.
     */
    public function monthlyYearly(Request $request): View
    {
        $filters = $request->only(['period', 'start_date', 'end_date']);
        $report = $this->reportsService->getMonthlyYearlyReport($filters);

        return view('admin.reports-management.monthly-yearly', $report);
    }

    /**
     * Display bag expiration report.
     */
    public function bagExpiration(Request $request): View
    {
        $filters = $request->only(['expiration_date_from', 'expiration_date_to', 'expiration_type', 'blood_type']);
        $report = $this->reportsService->getBagExpirationReport($filters);

        return view('admin.reports-management.bag-expiration', $report);
    }
}
