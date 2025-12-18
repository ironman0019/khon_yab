<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donor\UpdateDonorProfileRequest;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the donor profile edit form.
     */
    public function edit(): View
    {
        $donor = Auth::user()->donor;
        
        if (!$donor) {
            abort(404, 'Donor profile not found.');
        }

        $donor->load(['province', 'city', 'user']);
        $provinces = Province::all();
        $cities = City::where('province_id', $donor->province_id)->get();

        return view('donor.profile.edit', compact('donor', 'provinces', 'cities'));
    }

    /**
     * Update the donor profile.
     */
    public function update(UpdateDonorProfileRequest $request): RedirectResponse
    {
        $donor = Auth::user()->donor;
        
        if (!$donor) {
            abort(404, 'Donor profile not found.');
        }

        $donor->update($request->validated());

        // Also update user's full name if provided
        if ($request->filled('full_name')) {
            $donor->user->update([
                'full_name' => $request->full_name,
            ]);
        }

        return redirect()->route('donor.profile.edit')
            ->with('success', __('Profile updated successfully.'));
    }
}

