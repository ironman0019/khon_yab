<?php

namespace App\Http\Controllers\Receiver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receiver\UpdateReceiverProfileRequest;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the receiver profile edit form.
     */
    public function edit(): View
    {
        $receiver = Auth::user()->receiver;

        if (! $receiver) {
            abort(404, __('receiver.Receiver profile not found.'));
        }

        $receiver->load(['province', 'city', 'user']);
        $provinces = Province::all();
        $cities = City::where('province_id', $receiver->province_id)->get();

        return view('receiver.profile.edit', compact('receiver', 'provinces', 'cities'));
    }

    /**
     * Update the receiver profile.
     */
    public function update(UpdateReceiverProfileRequest $request): RedirectResponse
    {
        $receiver = Auth::user()->receiver;

        if (! $receiver) {
            abort(404, __('receiver.Receiver profile not found.'));
        }

        $receiver->update($request->validated());

        // Also update user's full name if provided
        if ($request->filled('full_name')) {
            $receiver->user->update([
                'full_name' => $request->full_name,
            ]);
        }

        return redirect()->route('receiver.profile.edit')
            ->with('success', __('receiver.Profile updated successfully.'));
    }
}
