<?php

namespace App\Http\Controllers\Donor;

use App\Enums\BloodInventoryStatus;
use App\Enums\DonationRecordStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Donor\StoreBloodDonationRecordRequest;
use App\Models\BloodDonationRecord;
use App\Models\BloodInventory;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // Get the most recent non-deleted donation record
        $lastDonation = BloodDonationRecord::where('donor_id', $donor->id)
            ->where('donation_date', '<=', $request->donation_date)
            ->latest('donation_date')
            ->first();
        
        if ($lastDonation) {
            $daysSinceLastDonation = now()->diffInDays($lastDonation->donation_date);
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

        try {
            DB::transaction(function () use ($request, $donor, $expirationDate) {
                // Ensure province_id is set
                $provinceId = $request->province_id ?? $donor->province_id;
                if (!$provinceId) {
                    throw new \Exception(__('Province is required. Please update your profile or select a province.'));
                }

                // Ensure blood_type and rh_factor are set
                if (!$donor->blood_type || !$donor->rh_factor) {
                    throw new \Exception(__('Blood type and RH factor are required. Please update your profile.'));
                }

                // Create donation record
                $donationRecord = BloodDonationRecord::create([
                    'donor_id' => $donor->id,
                    'donation_type' => $request->donation_type,
                    'amount_ml' => $request->amount_ml,
                    'donation_date' => $request->donation_date,
                    'expiration_date' => $expirationDate,
                    'status' => DonationRecordStatus::TestPending->value,
                    'submitted_by_donor' => true,
                    'province_id' => $provinceId,
                    'city_id' => $request->city_id ?? $donor->city_id,
                    'notes' => $request->notes,
                ]);

                // Automatically create blood inventory entry
                $bagId = $this->generateUniqueBagId($request->donation_date);

                BloodInventory::create([
                    'bag_id' => $bagId,
                    'blood_donation_record_id' => $donationRecord->id,
                    'province_id' => $provinceId,
                    'blood_type' => $donor->blood_type,
                    'rh_factor' => $donor->rh_factor,
                    'entry_date' => $request->donation_date,
                    'expiration_date' => $expirationDate,
                    'status' => BloodInventoryStatus::InStock->value,
                    'added_by' => null,
                    'notes' => 'Auto-generated from donation record #'.$donationRecord->id,
                ]);

                // Update donor's last donation date
                // Use the most recent donation date (which could be the one we just created)
                $mostRecentDonation = BloodDonationRecord::where('donor_id', $donor->id)
                    ->latest('donation_date')
                    ->first();
                
                $donor->update([
                    'last_donation_date' => $mostRecentDonation->donation_date,
                ]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Blood donation record creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

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

    /**
     * Generate a unique bag ID for blood inventory.
     */
    private function generateUniqueBagId(string $date): string
    {
        $datePrefix = date('Ymd', strtotime($date));
        $counter = 1;
        $bagId = sprintf('BAG-%s-%04d', $datePrefix, $counter);

        // Ensure uniqueness by checking if bag_id already exists
        while (BloodInventory::where('bag_id', $bagId)->exists()) {
            $counter++;
            $bagId = sprintf('BAG-%s-%04d', $datePrefix, $counter);
        }

        return $bagId;
    }
}

