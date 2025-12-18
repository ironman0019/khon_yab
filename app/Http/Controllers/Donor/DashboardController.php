<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the donor dashboard.
     */
    public function index(): View
    {
        $donor = Auth::user()->donor;
        
        if (!$donor) {
            abort(404, 'Donor profile not found.');
        }

        // Load relationships
        $donor->load(['province', 'city', 'user']);

        // Get donation statistics
        $donationRecords = $donor->bloodDonationRecords()
            ->with(['bloodTest'])
            ->latest('donation_date')
            ->get();

        $statistics = [
            'total_donations' => $donationRecords->count(),
            'total_amount_ml' => $donationRecords->sum('amount_ml'),
            'last_donation_date' => $donor->last_donation_date,
            'next_eligible_date' => $donor->nextEligibleDonationDate(),
            'days_until_next' => $donor->daysUntilNextDonation(),
            'can_donate_now' => $donor->canDonate(),
            'donations_by_status' => [
                'pending' => $donationRecords->where('status', 0)->count(),
                'safe' => $donationRecords->where('status', 1)->count(),
                'unsafe' => $donationRecords->where('status', 2)->count(),
                'discarded' => $donationRecords->where('status', 3)->count(),
            ],
            'donations_by_type' => [
                'whole_blood' => $donationRecords->where('donation_type', 0)->count(),
                'plasma' => $donationRecords->where('donation_type', 1)->count(),
                'platelets' => $donationRecords->where('donation_type', 2)->count(),
            ],
        ];

        // Get recent donations (last 5)
        $recentDonations = $donationRecords->take(5);

        return view('donor.dashboard.index', compact('donor', 'statistics', 'recentDonations'));
    }

    /**
     * Display donor reports.
     */
    public function reports(): View
    {
        $donor = Auth::user()->donor;
        
        if (!$donor) {
            abort(404, 'Donor profile not found.');
        }

        // Get all donation records with relationships
        $donationRecords = $donor->bloodDonationRecords()
            ->with(['province', 'city', 'bloodTest'])
            ->latest('donation_date')
            ->get();

        // Calculate statistics
        $statistics = [
            'total_donations' => $donationRecords->count(),
            'total_amount_ml' => $donationRecords->sum('amount_ml'),
            'average_amount_ml' => $donationRecords->count() > 0 ? round($donationRecords->avg('amount_ml'), 2) : 0,
            'last_donation_date' => $donor->last_donation_date,
            'next_eligible_date' => $donor->nextEligibleDonationDate(),
            'days_until_next' => $donor->daysUntilNextDonation(),
            'can_donate_now' => $donor->canDonate(),
            'donations_by_status' => [
                'pending' => $donationRecords->where('status', 0)->count(),
                'safe' => $donationRecords->where('status', 1)->count(),
                'unsafe' => $donationRecords->where('status', 2)->count(),
                'discarded' => $donationRecords->where('status', 3)->count(),
            ],
            'donations_by_type' => [
                'whole_blood' => $donationRecords->where('donation_type', 0)->count(),
                'plasma' => $donationRecords->where('donation_type', 1)->count(),
                'platelets' => $donationRecords->where('donation_type', 2)->count(),
            ],
            'donations_this_year' => $donationRecords->filter(function ($record) {
                return $record->donation_date->year === now()->year;
            })->count(),
            'donations_last_year' => $donationRecords->filter(function ($record) {
                return $record->donation_date->year === now()->year - 1;
            })->count(),
        ];

        return view('donor.reports.index', compact('donor', 'statistics', 'donationRecords'));
    }
}
