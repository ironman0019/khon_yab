<?php

namespace App\Http\Controllers\Laboratory;

use App\Enums\BloodInventoryStatus;
use App\Enums\DonationRecordStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BloodDonationManagement\StoreBloodDonationRecordRequest;
use App\Http\Requests\Admin\BloodDonationManagement\UpdateBloodDonationRecordRequest;
use App\Models\BloodDonationRecord;
use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BloodDonationRecordController extends Controller
{
    /**
     * Display a listing of blood donation records recorded by this laboratory.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = BloodDonationRecord::where('recorded_by_admin', $user->id)
            ->with(['donor.user', 'province', 'city']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('donor.user', function ($userQuery) use ($search) {
                    $userQuery->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by donor
        if ($request->filled('donor_id')) {
            $query->where('donor_id', $request->get('donor_id'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by donation type
        if ($request->filled('donation_type')) {
            $query->where('donation_type', $request->get('donation_type'));
        }

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->get('province_id'));
        }

        // Filter by date range
        if ($request->filled('donation_date_from')) {
            $query->where('donation_date', '>=', $request->get('donation_date_from'));
        }
        if ($request->filled('donation_date_to')) {
            $query->where('donation_date', '<=', $request->get('donation_date_to'));
        }

        $donationRecords = $query->latest('donation_date')->paginate(15)->withQueryString();
        $donors = Donor::with('user')->get();
        $provinces = Province::all();

        return view('laboratory.donation-records.index', compact('donationRecords', 'donors', 'provinces'));
    }

    /**
     * Show the form for creating a new blood donation record.
     */
    public function create(): View
    {
        $donors = Donor::with('user')->get();
        $provinces = Province::all();

        return view('laboratory.donation-records.create', compact('donors', 'provinces'));
    }

    /**
     * Store a newly created blood donation record in storage.
     */
    public function store(StoreBloodDonationRecordRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            // Get donor to access blood type and RH factor
            $donor = Donor::findOrFail($request->donor_id);

            // Create donation record
            $donationRecord = BloodDonationRecord::create([
                'donor_id' => $request->donor_id,
                'donation_type' => $request->donation_type,
                'amount_ml' => $request->amount_ml,
                'donation_date' => $request->donation_date,
                'expiration_date' => $request->expiration_date,
                'status' => $request->status ?? DonationRecordStatus::TestPending->value,
                'recorded_by_admin' => auth()->id(),
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'notes' => $request->notes,
                'submitted_by_donor' => false,
            ]);

            // Automatically create blood inventory entry
            $bagId = $this->generateUniqueBagId($request->donation_date);
            $provinceId = $request->province_id ?? $donor->province_id;

            BloodInventory::create([
                'bag_id' => $bagId,
                'blood_donation_record_id' => $donationRecord->id,
                'province_id' => $provinceId,
                'blood_type' => $donor->blood_type,
                'rh_factor' => $donor->rh_factor,
                'entry_date' => $request->donation_date,
                'expiration_date' => $request->expiration_date,
                'status' => BloodInventoryStatus::InStock->value,
                'added_by' => auth()->id(),
                'notes' => 'Auto-generated from donation record #'.$donationRecord->id,
            ]);
        });

        return redirect()->route('laboratory.donation-records.index')
            ->with('success', __('laboratory.Blood donation record created successfully.'));
    }

    /**
     * Display the specified blood donation record.
     */
    public function show(BloodDonationRecord $donation_record): View
    {
        $user = Auth::user();

        // Ensure this donation was recorded by the current laboratory
        if ($donation_record->recorded_by_admin !== $user->id) {
            abort(403, __('laboratory.Unauthorized access.'));
        }

        $donation_record->load([
            'donor.user',
            'province',
            'city',
            'recordedByAdmin',
            'bloodInventory',
            'bloodTest.tested_by',
        ]);

        return view('laboratory.donation-records.show', ['donationRecord' => $donation_record]);
    }

    /**
     * Show the form for editing the specified blood donation record.
     */
    public function edit(BloodDonationRecord $donation_record): View
    {
        $user = Auth::user();

        // Ensure this donation was recorded by the current laboratory
        if ($donation_record->recorded_by_admin !== $user->id) {
            abort(403, __('laboratory.Unauthorized access.'));
        }

        $donation_record->load(['donor.user', 'province', 'city']);
        $donors = Donor::with('user')->get();
        $provinces = Province::all();

        return view('laboratory.donation-records.edit', [
            'donationRecord' => $donation_record,
            'donors' => $donors,
            'provinces' => $provinces,
        ]);
    }

    /**
     * Update the specified blood donation record in storage.
     */
    public function update(UpdateBloodDonationRecordRequest $request, BloodDonationRecord $donation_record): RedirectResponse
    {
        $user = Auth::user();

        // Ensure this donation was recorded by the current laboratory
        if ($donation_record->recorded_by_admin !== $user->id) {
            abort(403, __('laboratory.Unauthorized access.'));
        }

        DB::transaction(function () use ($request, $donation_record) {
            // Update donation record
            $updateData = $request->only([
                'donor_id',
                'donation_type',
                'amount_ml',
                'donation_date',
                'expiration_date',
                'status',
                'province_id',
                'city_id',
                'notes',
            ]);

            $donation_record->update($updateData);

            // Update related inventory if it exists
            $inventory = $donation_record->bloodInventory()->first();
            if ($inventory) {
                $donor = $donation_record->donor;
                $inventory->update([
                    'blood_type' => $donor->blood_type,
                    'rh_factor' => $donor->rh_factor,
                    'entry_date' => $request->donation_date ?? $donation_record->donation_date,
                    'expiration_date' => $request->expiration_date ?? $donation_record->expiration_date,
                    'province_id' => $request->province_id ?? $inventory->province_id,
                ]);
            }
        });

        return redirect()->route('laboratory.donation-records.index')
            ->with('success', __('laboratory.Blood donation record updated successfully.'));
    }

    /**
     * Remove the specified blood donation record from storage.
     */
    public function destroy(BloodDonationRecord $donation_record): RedirectResponse
    {
        $user = Auth::user();

        // Ensure this donation was recorded by the current laboratory
        if ($donation_record->recorded_by_admin !== $user->id) {
            abort(403, __('laboratory.Unauthorized access.'));
        }

        $donation_record->delete();

        return redirect()->route('laboratory.donation-records.index')
            ->with('success', __('laboratory.Blood donation record deleted successfully.'));
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
