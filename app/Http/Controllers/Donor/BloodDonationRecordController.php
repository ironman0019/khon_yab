<?php

namespace App\Http\Controllers\Donor;

use App\Enums\DonationRecordStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Donor\StoreBloodDonationRecordRequest;
use App\Models\BloodDonationRecord;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BloodDonationRecordController extends Controller
{
    /**
     * Display a listing of the donor's blood donation records.
     */
    public function index(Request $request): View
    {
        $donor = Auth::user()->donor;
        
        if (!$donor) {
            abort(404, 'Donor profile not found.');
        }

        $query = BloodDonationRecord::where('donor_id', $donor->id)
            ->with(['province', 'city', 'bloodTest']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by donation type
        if ($request->filled('donation_type')) {
            $query->where('donation_type', $request->get('donation_type'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('donation_date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('donation_date', '<=', $request->get('date_to'));
        }

        $donationRecords = $query->latest('donation_date')->paginate(15)->withQueryString();

        return view('donor.donation-records.index', compact('donationRecords'));
    }

    /**
     * Show the form for creating a new blood donation record.
     */
    public function create(): View
    {
        $donor = Auth::user()->donor;
        
        if (!$donor) {
            abort(404, 'Donor profile not found.');
        }

        $provinces = Province::all();
        $cities = City::where('province_id', $donor->province_id)->get();

        return view('donor.donation-records.create', compact('provinces', 'cities', 'donor'));
    }

    /**
     * Store a newly created blood donation record in storage.
     */
    public function store(StoreBloodDonationRecordRequest $request): RedirectResponse
    {
        $donor = Auth::user()->donor;
        
        if (!$donor) {
            abort(404, 'Donor profile not found.');
        }

        // Check minimum days since last donation
        if ($donor->last_donation_date) {
            $daysSinceLastDonation = now()->diffInDays($donor->last_donation_date);
            $minDays = $this->getMinimumDaysForDonationType($request->donation_type);
            
            if ($daysSinceLastDonation < $minDays) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('You must wait at least :days days between donations of this type.', ['days' => $minDays]));
            }
        }

        // Auto-calculate expiration date if not provided
        $expirationDate = $request->expiration_date ?? $this->calculateExpirationDate(
            $request->donation_date,
            $request->donation_type
        );

        BloodDonationRecord::create([
            'donor_id' => $donor->id,
            'donation_type' => $request->donation_type,
            'amount_ml' => $request->amount_ml,
            'donation_date' => $request->donation_date,
            'expiration_date' => $expirationDate,
            'status' => DonationRecordStatus::TestPending->value,
            'submitted_by_donor' => true,
            'province_id' => $request->province_id ?? $donor->province_id,
            'city_id' => $request->city_id ?? $donor->city_id,
            'notes' => $request->notes,
        ]);

        // Update donor's last donation date
        $donor->update([
            'last_donation_date' => $request->donation_date,
        ]);

        return redirect()->route('donor.donation-records.index')
            ->with('success', __('Donation request submitted successfully.'));
    }

    /**
     * Display the specified blood donation record.
     */
    public function show(BloodDonationRecord $donation_record): View
    {
        $donor = Auth::user()->donor;
        
        if (!$donor || $donation_record->donor_id !== $donor->id) {
            abort(403, 'Unauthorized access.');
        }

        $donation_record->load(['province', 'city', 'bloodTest', 'bloodInventory']);

        return view('donor.donation-records.show', compact('donation_record'));
    }

    /**
     * Cancel a pending donation record.
     */
    public function cancel(BloodDonationRecord $donation_record): RedirectResponse
    {
        $donor = Auth::user()->donor;
        
        if (!$donor || $donation_record->donor_id !== $donor->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow cancellation if status is Test Pending and submitted by donor
        if ($donation_record->status !== DonationRecordStatus::TestPending->value || !$donation_record->submitted_by_donor) {
            return redirect()->route('donor.donation-records.index')
                ->with('error', __('Only pending donation requests submitted by you can be cancelled.'));
        }

        $donation_record->delete();

        return redirect()->route('donor.donation-records.index')
            ->with('success', __('Donation request cancelled successfully.'));
    }

    /**
     * Get minimum days required between donations for a specific donation type.
     */
    protected function getMinimumDaysForDonationType(int $donationType): int
    {
        return match ($donationType) {
            0 => 56, // Whole Blood - 8 weeks
            1 => 28, // Plasma - 4 weeks
            2 => 7,  // Platelets - 1 week
            default => 56,
        };
    }

    /**
     * Calculate expiration date based on donation date and type.
     */
    protected function calculateExpirationDate(string $donationDate, int $donationType): string
    {
        $days = match ($donationType) {
            0 => 42, // Whole Blood - 42 days
            1 => 365, // Plasma - 1 year (frozen)
            2 => 5,   // Platelets - 5 days
            default => 42,
        };

        return date('Y-m-d', strtotime($donationDate . " +{$days} days"));
    }
}

