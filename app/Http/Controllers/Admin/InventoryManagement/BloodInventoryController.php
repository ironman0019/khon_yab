<?php

namespace App\Http\Controllers\Admin\InventoryManagement;

use App\Enums\BloodInventoryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InventoryManagement\StoreBloodInventoryRequest;
use App\Http\Requests\Admin\InventoryManagement\UpdateBloodInventoryRequest;
use App\Models\BloodInventory;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BloodInventoryController extends Controller
{
    /**
     * Display a listing of blood inventory.
     */
    public function index(Request $request): View
    {
        $query = BloodInventory::with(['bloodDonationRecord.donor.user', 'province', 'addedBy', 'removedBy']);

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->get('blood_type'));
        }

        // Filter by RH factor
        if ($request->filled('rh_factor')) {
            $query->where('rh_factor', $request->get('rh_factor'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->get('province_id'));
        }

        // Filter by expiration date (expired, expiring soon, valid)
        if ($request->filled('expiration_filter')) {
            $filter = $request->get('expiration_filter');
            $today = now()->toDateString();
            $sevenDaysFromNow = now()->addDays(7)->toDateString();

            match ($filter) {
                'expired' => $query->where('expiration_date', '<', $today),
                'expiring_soon' => $query->whereBetween('expiration_date', [$today, $sevenDaysFromNow]),
                'valid' => $query->where('expiration_date', '>', $sevenDaysFromNow),
                default => null,
            };
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('bag_id', 'like', "%{$search}%")
                    ->orWhereHas('bloodDonationRecord.donor.user', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        $inventory = $query->latest('entry_date')->paginate(15);
        $provinces = Province::all();

        return view('admin.inventory-management.index', compact('inventory', 'provinces'));
    }

    /**
     * Show the form for creating a new blood inventory entry.
     */
    public function create(): View
    {
        $provinces = Province::all();

        return view('admin.inventory-management.create', compact('provinces'));
    }

    /**
     * Store a newly created blood inventory entry.
     */
    public function store(StoreBloodInventoryRequest $request): RedirectResponse
    {
        BloodInventory::create([
            'bag_id' => $request->bag_id,
            'blood_donation_record_id' => $request->blood_donation_record_id,
            'province_id' => $request->province_id,
            'blood_type' => $request->blood_type,
            'rh_factor' => $request->rh_factor,
            'entry_date' => $request->entry_date,
            'expiration_date' => $request->expiration_date,
            'status' => BloodInventoryStatus::InStock->value,
            'added_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.inventory-management.index')
            ->with('success', __('admin.Blood inventory entry created successfully.'));
    }

    /**
     * Display the specified blood inventory entry.
     */
    public function show(BloodInventory $inventory_management): View
    {
        $inventory_management->load([
            'bloodDonationRecord.donor.user',
            'province',
            'addedBy',
            'removedBy',
        ]);

        return view('admin.inventory-management.show', ['bloodInventory' => $inventory_management]);
    }

    /**
     * Show the form for editing the specified blood inventory entry.
     */
    public function edit(BloodInventory $inventory_management): View
    {
        $inventory_management->load(['bloodDonationRecord', 'province']);
        $provinces = Province::all();

        return view('admin.inventory-management.edit', ['bloodInventory' => $inventory_management, 'provinces' => $provinces]);
    }

    /**
     * Update the specified blood inventory entry.
     */
    public function update(UpdateBloodInventoryRequest $request, BloodInventory $inventory_management): RedirectResponse
    {
        $inventory_management->update($request->validated());

        return redirect()->route('admin.inventory-management.index')
            ->with('success', __('admin.Blood inventory entry updated successfully.'));
    }

    /**
     * Remove the specified blood inventory entry.
     */
    public function destroy(BloodInventory $inventory_management): RedirectResponse
    {
        $inventory_management->delete();

        return redirect()->route('admin.inventory-management.index')
            ->with('success', __('admin.Blood inventory entry deleted successfully.'));
    }

    /**
     * Mark inventory as used.
     */
    public function markAsUsed(BloodInventory $bloodInventory): RedirectResponse
    {
        if ($bloodInventory->status !== BloodInventoryStatus::InStock->value) {
            return redirect()->route('admin.inventory-management.index')
                ->with('error', __('admin.Only in-stock inventory can be marked as used.'));
        }

        $bloodInventory->update([
            'status' => BloodInventoryStatus::Used->value,
            'exit_date' => now(),
            'removed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.inventory-management.index')
            ->with('success', __('admin.Blood inventory marked as used successfully.'));
    }

    /**
     * Mark inventory as expired.
     */
    public function markAsExpired(BloodInventory $bloodInventory): RedirectResponse
    {
        if ($bloodInventory->status !== BloodInventoryStatus::InStock->value) {
            return redirect()->route('admin.inventory-management.index')
                ->with('error', __('admin.Only in-stock inventory can be marked as expired.'));
        }

        $bloodInventory->update([
            'status' => BloodInventoryStatus::Expired->value,
            'removed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.inventory-management.index')
            ->with('success', __('admin.Blood inventory marked as expired successfully.'));
    }
}
