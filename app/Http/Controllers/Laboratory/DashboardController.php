<?php

namespace App\Http\Controllers\Laboratory;

use App\Enums\BloodRequestStatus;
use App\Enums\DonationRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the laboratory dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $laboratory = $user->laboratory;

        if (! $laboratory) {
            abort(404, __('laboratory.Laboratory profile not found.'));
        }

        // Load relationships
        $laboratory->load(['province', 'city', 'user']);

        // Get blood request statistics
        $bloodRequests = $user->bloodRequests()
            ->with(['province', 'city', 'approvedBy'])
            ->latest()
            ->get();

        $requestStatistics = [
            'total_requests' => $bloodRequests->count(),
            'pending_requests' => $bloodRequests->where('status', BloodRequestStatus::Pending->value)->count(),
            'approved_requests' => $bloodRequests->where('status', BloodRequestStatus::Approved->value)->count(),
            'completed_requests' => $bloodRequests->where('status', BloodRequestStatus::Completed->value)->count(),
            'rejected_requests' => $bloodRequests->where('status', BloodRequestStatus::Rejected->value)->count(),
        ];

        // Get blood donation records recorded by this laboratory
        $bloodDonations = $user->recordedBloodDonations()
            ->with(['donor.user', 'province', 'city'])
            ->latest('donation_date')
            ->get();

        $donationStatistics = [
            'total_donations' => $bloodDonations->count(),
            'test_pending' => $bloodDonations->where('status', DonationRecordStatus::TestPending->value)->count(),
            'safe' => $bloodDonations->where('status', DonationRecordStatus::Safe->value)->count(),
            'unsafe' => $bloodDonations->where('status', DonationRecordStatus::Unsafe->value)->count(),
            'discarded' => $bloodDonations->where('status', DonationRecordStatus::Discarded->value)->count(),
        ];

        // Get recent requests (last 5)
        $recentRequests = $bloodRequests->take(5);

        // Get recent donations (last 5)
        $recentDonations = $bloodDonations->take(5);

        return view('laboratory.dashboard.index', compact(
            'laboratory',
            'requestStatistics',
            'donationStatistics',
            'recentRequests',
            'recentDonations'
        ));
    }
}
