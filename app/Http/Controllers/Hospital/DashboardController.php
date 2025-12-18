<?php

namespace App\Http\Controllers\Hospital;

use App\Enums\BloodRequestStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the hospital dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $hospitalUser = $user->hospitalUser;

        if (! $hospitalUser) {
            abort(404, __('hospital.Hospital profile not found.'));
        }

        // Load relationships
        $hospitalUser->load(['province', 'city', 'user']);

        // Get blood request statistics
        $bloodRequests = $user->bloodRequests()
            ->with(['province', 'city', 'approvedBy'])
            ->latest()
            ->get();

        $statistics = [
            'total_requests' => $bloodRequests->count(),
            'pending_requests' => $bloodRequests->where('status', BloodRequestStatus::Pending->value)->count(),
            'approved_requests' => $bloodRequests->where('status', BloodRequestStatus::Approved->value)->count(),
            'completed_requests' => $bloodRequests->where('status', BloodRequestStatus::Completed->value)->count(),
            'rejected_requests' => $bloodRequests->where('status', BloodRequestStatus::Rejected->value)->count(),
        ];

        // Get recent requests (last 5)
        $recentRequests = $bloodRequests->take(5);

        return view('hospital.dashboard.index', compact('hospitalUser', 'statistics', 'recentRequests'));
    }
}
