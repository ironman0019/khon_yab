<?php

namespace App\Http\Controllers\Admin\BloodRequestManagement;

use App\Enums\BloodRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BloodRequestManagement\ApproveBloodRequestRequest;
use App\Http\Requests\Admin\BloodRequestManagement\RejectBloodRequestRequest;
use App\Http\Requests\Admin\BloodRequestManagement\UpdateBloodRequestRequest;
use App\Models\BloodRequest;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BloodRequestController extends Controller
{
    /**
     * Display a listing of blood requests.
     */
    public function index(Request $request): View
    {
        $query = BloodRequest::with(['requestedBy:id,full_name,email', 'approvedBy:id,full_name', 'province', 'city']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->get('blood_type'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('medical_center', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhereHas('requestedBy', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $bloodRequests = $query->latest()->paginate(15);
        $provinces = Province::all();

        return view('admin.blood-request-management.index', compact('bloodRequests', 'provinces'));
    }

    /**
     * Display the specified blood request.
     */
    public function show(BloodRequest $bloodRequest): View
    {
        $bloodRequest->load([
            'requestedBy:id,full_name,email',
            'approvedBy:id,full_name,email',
            'province',
            'city',
        ]);

        return view('admin.blood-request-management.show', compact('bloodRequest'));
    }

    /**
     * Show the form for editing the specified blood request.
     */
    public function edit(BloodRequest $bloodRequest): View
    {
        $bloodRequest->load(['province', 'city']);
        $provinces = Province::all();

        return view('admin.blood-request-management.edit', compact('bloodRequest', 'provinces'));
    }

    /**
     * Approve a blood request.
     */
    public function approve(ApproveBloodRequestRequest $request, BloodRequest $bloodRequest): RedirectResponse
    {
        if ($bloodRequest->status !== BloodRequestStatus::Pending->value) {
            return redirect()->route('admin.blood-request-management.index')
                ->with('error', __('admin.Only pending requests can be approved.'));
        }

        // Check available inventory
        $availableInventory = \App\Models\BloodInventory::where('blood_type', $bloodRequest->blood_type)
            ->where('rh_factor', $bloodRequest->rh_factor)
            ->where('status', \App\Enums\BloodInventoryStatus::InStock->value)
            ->where('expiration_date', '>', now())
            ->orderBy('expiration_date', 'asc')
            ->get();

        if ($availableInventory->count() < $bloodRequest->number_of_bags) {
            return redirect()->route('admin.blood-request-management.index')
                ->with('error', __('admin.Insufficient inventory available. Available: :available, Requested: :requested', [
                    'available' => $availableInventory->count(),
                    'requested' => $bloodRequest->number_of_bags,
                ]));
        }

        // Reduce inventory
        $bagsToDeduct = $bloodRequest->number_of_bags;
        foreach ($availableInventory as $inventoryItem) {
            if ($bagsToDeduct <= 0) {
                break;
            }

            $inventoryItem->update([
                'status' => \App\Enums\BloodInventoryStatus::Used->value,
                'exit_date' => now(),
                'removed_by' => auth()->id(),
                'notes' => 'Used for blood request #'.$bloodRequest->id.($inventoryItem->notes ? ' - '.$inventoryItem->notes : ''),
            ]);

            $bagsToDeduct--;
        }

        $bloodRequest->update([
            'status' => BloodRequestStatus::Approved->value,
            'approved_by' => auth()->id(),
            'approval_date' => now(),
            'notes' => $request->notes ?? $bloodRequest->notes,
        ]);

        return redirect()->route('admin.blood-request-management.index')
            ->with('success', __('admin.Blood request approved successfully.'));
    }

    /**
     * Reject a blood request.
     */
    public function reject(RejectBloodRequestRequest $request, BloodRequest $bloodRequest): RedirectResponse
    {
        if ($bloodRequest->status !== BloodRequestStatus::Pending->value) {
            return redirect()->route('admin.blood-request-management.index')
                ->with('error', __('admin.Only pending requests can be rejected.'));
        }

        $bloodRequest->update([
            'status' => BloodRequestStatus::Rejected->value,
            'approved_by' => auth()->id(),
            'approval_date' => now(),
            'rejection_reason' => $request->rejection_reason,
            'notes' => $request->notes ?? $bloodRequest->notes,
        ]);

        return redirect()->route('admin.blood-request-management.index')
            ->with('success', __('admin.Blood request rejected successfully.'));
    }

    /**
     * Mark a blood request as completed.
     */
    public function complete(BloodRequest $bloodRequest): RedirectResponse
    {
        if ($bloodRequest->status !== BloodRequestStatus::Approved->value) {
            return redirect()->route('admin.blood-request-management.index')
                ->with('error', __('admin.Only approved requests can be marked as completed.'));
        }

        $bloodRequest->update([
            'status' => BloodRequestStatus::Completed->value,
        ]);

        return redirect()->route('admin.blood-request-management.index')
            ->with('success', __('admin.Blood request marked as completed successfully.'));
    }

    /**
     * Update the specified blood request.
     */
    public function update(UpdateBloodRequestRequest $request, BloodRequest $bloodRequest): RedirectResponse
    {
        // Only allow updating pending requests
        if ($bloodRequest->status !== BloodRequestStatus::Pending->value) {
            return redirect()->route('admin.blood-request-management.index')
                ->with('error', __('admin.Only pending requests can be updated.'));
        }

        $bloodRequest->update($request->validated());

        return redirect()->route('admin.blood-request-management.index')
            ->with('success', __('admin.Blood request updated successfully.'));
    }
}
