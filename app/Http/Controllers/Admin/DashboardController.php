<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Services\Admin\DashboardService;
use Illuminate\Http\JsonResponse;
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

    /**
     * Get pending blood request notifications.
     */
    public function notifications(): JsonResponse
    {
        $pendingRequests = BloodRequest::with(['requestedBy:id,full_name', 'province', 'city'])
            ->where('status', 0) // Pending status
            ->latest()
            ->limit(10)
            ->get();

        $count = BloodRequest::where('status', 0)->count();

        return response()->json([
            'count' => $count,
            'notifications' => $pendingRequests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'patient_name' => $request->patient_name,
                    'blood_type' => $request->blood_type . $request->rh_factor,
                    'number_of_bags' => $request->number_of_bags,
                    'medical_center' => $request->medical_center,
                    'requested_by' => $request->requestedBy->full_name ?? 'Unknown',
                    'province' => $request->province->name ?? '',
                    'city' => $request->city->name ?? '',
                    'created_at' => $request->created_at->diffForHumans(),
                    'url' => route('admin.blood-request-management.show', ['bloodRequest' => $request->id]),
                ];
            }),
        ]);
    }
}
