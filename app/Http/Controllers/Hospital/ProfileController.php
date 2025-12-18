<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\UpdateHospitalProfileRequest;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the hospital profile edit form.
     */
    public function edit(?string $updateRoute = null): View
    {
        $user = Auth::user();
        $hospitalUser = $user->hospitalUser;

        if (! $hospitalUser) {
            abort(404, __('hospital.Hospital profile not found.'));
        }

        $hospitalUser->load(['province', 'city', 'user']);
        $provinces = Province::all();
        $cities = $hospitalUser->province_id
            ? City::where('province_id', $hospitalUser->province_id)->get()
            : collect();

        $updateRoute = $updateRoute ?? 'hospital.profile.update';

        return view('hospital.profile.edit', compact('hospitalUser', 'provinces', 'cities', 'updateRoute'));
    }

    /**
     * Update the hospital profile.
     */
    public function update(UpdateHospitalProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $hospitalUser = $user->hospitalUser;

        if (! $hospitalUser) {
            abort(404, __('hospital.Hospital profile not found.'));
        }

        $hospitalUser->update($request->validated());

        return redirect()->route('hospital.profile.edit')
            ->with('success', __('hospital.Hospital profile updated successfully.'));
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
            return redirect()->route('hospital.blood-requests.index')
                ->with('error', __('hospital.No receipts available for download.'));
        }

        // For now, redirect to the list page
        // In a real application, you might want to generate a PDF or ZIP file
        return redirect()->route('hospital.blood-requests.index')
            ->with('info', __('hospital.Please use the print button on individual requests to download receipts.'));
    }
}
