<?php

namespace App\Http\Controllers\User\HospitalManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\HospitalManagement\UpdateHospitalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HospitalController extends Controller
{
    /**
     * Display the hospital user profile.
     */
    public function index(): View
    {
        $user = auth()->user();
        $user->load('bloodRequests');

        return view('user.hospital-management.index', compact('user'));
    }

    /**
     * Show the form for editing the hospital user profile.
     */
    public function edit(): View
    {
        $user = auth()->user();

        return view('user.hospital-management.edit', compact('user'));
    }

    /**
     * Update the hospital user profile.
     */
    public function update(UpdateHospitalRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        if (isset($validated['password']) && ! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Only allow updating certain fields for hospital users
        $allowedFields = ['full_name', 'email', 'password'];
        $updateData = array_intersect_key($validated, array_flip($allowedFields));

        $user->update($updateData);

        return redirect()->route('user.hospital-management.index')
            ->with('success', 'Profile updated successfully.');
    }
}
