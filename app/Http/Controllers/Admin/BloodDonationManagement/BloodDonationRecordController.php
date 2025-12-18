<?php

namespace App\Http\Controllers\Admin\BloodDonationManagement;

use App\Enums\BloodInventoryStatus;
use App\Enums\DonationRecordStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BloodDonationManagement\StoreBloodDonationRecordRequest;
use App\Http\Requests\Admin\BloodDonationManagement\StoreBloodTestRequest;
use App\Http\Requests\Admin\BloodDonationManagement\UpdateBloodDonationRecordRequest;
use App\Http\Requests\Admin\BloodDonationManagement\UpdateBloodTestRequest;
use App\Models\BloodDonationRecord;
use App\Models\BloodInventory;
use App\Models\BloodTest;
use App\Models\Donor;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BloodDonationRecordController extends Controller
{
    /**
     * Display a listing of blood donation records.
     */
    public function index(Request $request): View
    {
        $query = BloodDonationRecord::with(['donor.user', 'province', 'city', 'recordedByAdmin']);

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

        return view('admin.blood-donation-management.index', compact('donationRecords', 'donors', 'provinces'));
    }

    /**
     * Show the form for creating a new blood donation record.
     */
    public function create(): View
    {
        $donors = Donor::with('user')->get();
        $provinces = Province::all();

        return view('admin.blood-donation-management.create', compact('donors', 'provinces'));
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

        return redirect()->route('admin.blood-donation-management.index')
            ->with('success', __('admin.Blood donation record created successfully.'));
    }

    /**
     * Display the specified blood donation record.
     */
    public function show(BloodDonationRecord $blood_donation_management): View
    {
        $blood_donation_management->load([
            'donor.user',
            'province',
            'city',
            'recordedByAdmin',
            'bloodInventory',
            'bloodTest.tested_by',
        ]);

        return view('admin.blood-donation-management.show', ['donationRecord' => $blood_donation_management]);
    }

    /**
     * Show the form for editing the specified blood donation record.
     */
    public function edit(BloodDonationRecord $blood_donation_management): View
    {
        $blood_donation_management->load(['donor.user', 'province', 'city']);
        $donors = Donor::with('user')->get();
        $provinces = Province::all();

        return view('admin.blood-donation-management.edit', [
            'donationRecord' => $blood_donation_management,
            'donors' => $donors,
            'provinces' => $provinces,
        ]);
    }

    /**
     * Update the specified blood donation record in storage.
     */
    public function update(UpdateBloodDonationRecordRequest $request, BloodDonationRecord $blood_donation_management): RedirectResponse
    {
        DB::transaction(function () use ($request, $blood_donation_management) {
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

            $blood_donation_management->update($updateData);

            // Update related inventory if it exists
            $inventory = $blood_donation_management->bloodInventory()->first();
            if ($inventory) {
                $donor = $blood_donation_management->donor;
                $inventory->update([
                    'blood_type' => $donor->blood_type,
                    'rh_factor' => $donor->rh_factor,
                    'entry_date' => $request->donation_date ?? $blood_donation_management->donation_date,
                    'expiration_date' => $request->expiration_date ?? $blood_donation_management->expiration_date,
                    'province_id' => $request->province_id ?? $inventory->province_id,
                ]);
            }
        });

        return redirect()->route('admin.blood-donation-management.index')
            ->with('success', __('admin.Blood donation record updated successfully.'));
    }

    /**
     * Remove the specified blood donation record from storage.
     */
    public function destroy(BloodDonationRecord $blood_donation_management): RedirectResponse
    {
        $blood_donation_management->delete();

        return redirect()->route('admin.blood-donation-management.index')
            ->with('success', __('admin.Blood donation record deleted successfully.'));
    }

    /**
     * Display printable receipt for the donation record.
     */
    public function printReceipt(BloodDonationRecord $blood_donation_record): View
    {
        $blood_donation_record->load([
            'donor.user',
            'province',
            'city',
            'recordedByAdmin',
        ]);

        return view('admin.blood-donation-management.print', ['donationRecord' => $blood_donation_record]);
    }

    /**
     * Show the form for creating a new blood test.
     */
    public function createTest(BloodDonationRecord $blood_donation_management): View
    {
        // Only allow adding tests to records with Test Pending status
        if ($blood_donation_management->status !== DonationRecordStatus::TestPending->value) {
            abort(403, __('admin.Test results can only be added to donation records with Test Pending status.'));
        }

        // Check if test already exists
        if ($blood_donation_management->bloodTest) {
            return redirect()->route('admin.blood-donation-management.show', $blood_donation_management)
                ->with('error', __('admin.Test results already exist. Please edit the existing test.'));
        }

        return view('admin.blood-donation-management.test.create', [
            'donationRecord' => $blood_donation_management,
        ]);
    }

    /**
     * Store a newly created blood test in storage.
     */
    public function storeTest(StoreBloodTestRequest $request, BloodDonationRecord $blood_donation_management): RedirectResponse
    {
        // Only allow adding tests to records with Test Pending status
        if ($blood_donation_management->status !== DonationRecordStatus::TestPending->value) {
            abort(403, __('admin.Test results can only be added to donation records with Test Pending status.'));
        }

        // Check if test already exists
        if ($blood_donation_management->bloodTest) {
            return redirect()->route('admin.blood-donation-management.show', $blood_donation_management)
                ->with('error', __('admin.Test results already exist.'));
        }

        DB::transaction(function () use ($request, $blood_donation_management) {
            // Calculate overall_result: unsafe (1) if ANY test is positive (1), otherwise safe (0)
            $overallResult = ($request->hiv_result || $request->hbv_result || $request->hcv_result || $request->syphilis_result || $request->malaria_result) ? 1 : 0;

            // Generate test logs with timestamp and summary
            $testData = new \stdClass;
            $testData->hiv_result = $request->hiv_result;
            $testData->hbv_result = $request->hbv_result;
            $testData->hcv_result = $request->hcv_result;
            $testData->syphilis_result = $request->syphilis_result;
            $testData->malaria_result = $request->malaria_result;
            $testData->test_date = $request->test_date;
            $testLogs = $this->generateTestLogs($testData, $overallResult);

            // Create blood test
            $bloodTest = BloodTest::create([
                'blood_donation_record_id' => $blood_donation_management->id,
                'hiv_result' => $request->hiv_result,
                'hbv_result' => $request->hbv_result,
                'hcv_result' => $request->hcv_result,
                'syphilis_result' => $request->syphilis_result,
                'malaria_result' => $request->malaria_result,
                'overall_result' => $overallResult,
                'test_date' => $request->test_date,
                'tested_by' => auth()->id(),
                'test_logs' => $request->test_logs ? $testLogs."\n\nAdditional Notes:\n".$request->test_logs : $testLogs,
            ]);

            // Update donation record status
            $newStatus = $overallResult === 1 ? DonationRecordStatus::Unsafe->value : DonationRecordStatus::Safe->value;
            $blood_donation_management->update(['status' => $newStatus]);

            // If unsafe, discard all related inventory
            if ($overallResult === 1) {
                $blood_donation_management->bloodInventory()->each(function ($inventory) {
                    $existingNotes = $inventory->notes ?? '';
                    $discardNote = "\n\nDiscarded on ".now()->format('Y-m-d H:i:s').' due to unsafe test results. Tested by: '.auth()->user()->full_name;
                    $inventory->update([
                        'status' => BloodInventoryStatus::Discarded->value,
                        'removed_by' => auth()->id(),
                        'exit_date' => now()->toDateString(),
                        'notes' => $existingNotes.$discardNote,
                    ]);
                });
            }
        });

        return redirect()->route('admin.blood-donation-management.show', $blood_donation_management)
            ->with('success', __('admin.Blood test results recorded successfully.'));
    }

    /**
     * Show the form for editing the specified blood test.
     */
    public function editTest(BloodDonationRecord $blood_donation_management): View
    {
        $bloodTest = $blood_donation_management->bloodTest;

        if (! $bloodTest) {
            return redirect()->route('admin.blood-donation-management.show', $blood_donation_management)
                ->with('error', __('admin.No test results found. Please add test results first.'));
        }

        // Don't allow editing if donation record is already discarded
        if ($blood_donation_management->status === DonationRecordStatus::Discarded->value) {
            abort(403, __('admin.Cannot edit test results for discarded donation records.'));
        }

        $bloodTest->load('tested_by');

        return view('admin.blood-donation-management.test.edit', [
            'donationRecord' => $blood_donation_management,
            'bloodTest' => $bloodTest,
        ]);
    }

    /**
     * Update the specified blood test in storage.
     */
    public function updateTest(UpdateBloodTestRequest $request, BloodDonationRecord $blood_donation_management): RedirectResponse
    {
        $bloodTest = $blood_donation_management->bloodTest;

        if (! $bloodTest) {
            return redirect()->route('admin.blood-donation-management.show', $blood_donation_management)
                ->with('error', __('admin.No test results found.'));
        }

        // Don't allow editing if donation record is already discarded
        if ($blood_donation_management->status === DonationRecordStatus::Discarded->value) {
            abort(403, __('admin.Cannot edit test results for discarded donation records.'));
        }

        DB::transaction(function () use ($request, $blood_donation_management, $bloodTest) {
            // Calculate overall_result: unsafe (1) if ANY test is positive (1), otherwise safe (0)
            $hivResult = $request->has('hiv_result') ? $request->hiv_result : $bloodTest->hiv_result;
            $hbvResult = $request->has('hbv_result') ? $request->hbv_result : $bloodTest->hbv_result;
            $hcvResult = $request->has('hcv_result') ? $request->hcv_result : $bloodTest->hcv_result;
            $syphilisResult = $request->has('syphilis_result') ? $request->syphilis_result : $bloodTest->syphilis_result;
            $malariaResult = $request->has('malaria_result') ? $request->malaria_result : $bloodTest->malaria_result;

            $overallResult = ($hivResult || $hbvResult || $hcvResult || $syphilisResult || $malariaResult) ? 1 : 0;

            // Generate test logs with timestamp and summary
            $testData = new \stdClass;
            $testData->hiv_result = $hivResult;
            $testData->hbv_result = $hbvResult;
            $testData->hcv_result = $hcvResult;
            $testData->syphilis_result = $syphilisResult;
            $testData->malaria_result = $malariaResult;
            $testData->test_date = $request->test_date ?? $bloodTest->test_date->format('Y-m-d');
            $testLogs = $this->generateTestLogs($testData, $overallResult);

            // Update blood test
            $updateData = [
                'hiv_result' => $hivResult,
                'hbv_result' => $hbvResult,
                'hcv_result' => $hcvResult,
                'syphilis_result' => $syphilisResult,
                'malaria_result' => $malariaResult,
                'overall_result' => $overallResult,
            ];

            if ($request->has('test_date')) {
                $updateData['test_date'] = $request->test_date;
            }

            if ($request->has('test_logs')) {
                $updateData['test_logs'] = $request->test_logs ? $testLogs."\n\nAdditional Notes:\n".$request->test_logs : $testLogs;
            } else {
                $updateData['test_logs'] = $testLogs;
            }

            $bloodTest->update($updateData);

            // Update donation record status
            $newStatus = $overallResult === 1 ? DonationRecordStatus::Unsafe->value : DonationRecordStatus::Safe->value;
            $blood_donation_management->update(['status' => $newStatus]);

            // If unsafe, discard all related inventory
            if ($overallResult === 1) {
                $blood_donation_management->bloodInventory()->each(function ($inventory) {
                    $existingNotes = $inventory->notes ?? '';
                    $discardNote = "\n\nUpdated and discarded on ".now()->format('Y-m-d H:i:s').' due to unsafe test results. Updated by: '.auth()->user()->full_name;
                    $inventory->update([
                        'status' => BloodInventoryStatus::Discarded->value,
                        'removed_by' => auth()->id(),
                        'exit_date' => now()->toDateString(),
                        'notes' => $existingNotes.$discardNote,
                    ]);
                });
            } else {
                // If safe, restore inventory that was previously discarded due to tests
                $blood_donation_management->bloodInventory()->each(function ($inventory) {
                    if ($inventory->status === BloodInventoryStatus::Discarded->value) {
                        $inventory->update([
                            'status' => BloodInventoryStatus::InStock->value,
                            'removed_by' => null,
                            'exit_date' => null,
                        ]);
                    }
                });
            }
        });

        return redirect()->route('admin.blood-donation-management.show', $blood_donation_management)
            ->with('success', __('admin.Blood test results updated successfully.'));
    }

    /**
     * Generate test logs with timestamp and summary.
     */
    private function generateTestLogs($request, int $overallResult): string
    {
        $testNames = [
            'hiv_result' => 'HIV',
            'hbv_result' => 'HBV',
            'hcv_result' => 'HCV',
            'syphilis_result' => 'Syphilis',
            'malaria_result' => 'Malaria',
        ];

        $log = 'Test Log - '.now()->format('Y-m-d H:i:s')."\n";
        $log .= 'Test Date: '.(is_object($request) && isset($request->test_date) ? $request->test_date : date('Y-m-d'))."\n";
        $log .= 'Tested By: '.auth()->user()->full_name."\n\n";
        $log .= "Test Results:\n";

        foreach ($testNames as $field => $name) {
            $result = is_object($request) && isset($request->$field) ? $request->$field : 0;
            $status = $result === 1 ? 'Positive' : 'Negative';
            $log .= "- {$name}: {$status}\n";
        }

        $log .= "\nOverall Result: ".($overallResult === 1 ? 'Unsafe' : 'Safe');

        return $log;
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
