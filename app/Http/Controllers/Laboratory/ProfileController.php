<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboratory\UpdateLaboratoryProfileRequest;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the laboratory profile edit form.
     */
    public function edit(?string $updateRoute = null): View
    {
        $user = Auth::user();
        $laboratory = $user->laboratory;

        if (! $laboratory) {
            abort(404, __('laboratory.Laboratory profile not found.'));
        }

        $laboratory->load(['province', 'city', 'user']);
        $provinces = Province::all();
        $cities = $laboratory->province_id
            ? City::where('province_id', $laboratory->province_id)->get()
            : collect();

        $updateRoute = $updateRoute ?? 'laboratory.profile.update';

        return view('laboratory.profile.edit', compact('laboratory', 'provinces', 'cities', 'updateRoute'));
    }

    /**
     * Update the laboratory profile.
     */
    public function update(UpdateLaboratoryProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $laboratory = $user->laboratory;

        if (! $laboratory) {
            abort(404, __('laboratory.Laboratory profile not found.'));
        }

        $laboratory->update($request->validated());

        return redirect()->route('laboratory.profile.edit')
            ->with('success', __('laboratory.Laboratory profile updated successfully.'));
    }

    /**
     * Download receipts for approved/completed requests.
     */
    public function downloadReceipts(): RedirectResponse
    {
        $user = Auth::user();

        $bloodRequests = \App\Models\BloodRequest::where('requested_by', $user->id)
            ->whereIn('status', [
                \App\Enums\BloodRequestStatus::Approved->value,
                \App\Enums\BloodRequestStatus::Completed->value,
            ])
            ->latest()
            ->get();

        if ($bloodRequests->isEmpty()) {
            return redirect()->route('laboratory.blood-requests.index')
                ->with('error', __('laboratory.No receipts available for download.'));
        }

        // For now, redirect to the list page
        // In a real application, you might want to generate a PDF or ZIP file
        return redirect()->route('laboratory.blood-requests.index')
            ->with('info', __('laboratory.Please use the print button on individual requests to download receipts.'));
    }
}
