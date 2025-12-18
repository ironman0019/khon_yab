<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Controllers\Hospital\ProfileController as HospitalProfileController;
use App\Http\Requests\Hospital\UpdateHospitalProfileRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // If user is a hospital user, delegate to hospital profile controller
        if ($user->user_type === UserType::HospitalUser->value) {
            $hospitalController = new HospitalProfileController();
            return $hospitalController->edit('profile.update');
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // If user is a hospital user, validate and update using hospital profile request
        if ($user->user_type === UserType::HospitalUser->value) {
            $formRequest = new UpdateHospitalProfileRequest();
            $validated = $request->validate($formRequest->rules(), $formRequest->messages());
            $hospitalUser = $user->hospitalUser;

            if (! $hospitalUser) {
                abort(404, __('hospital.Hospital profile not found.'));
            }

            $hospitalUser->update($validated);

            return Redirect::route('profile.edit')
                ->with('success', __('hospital.Hospital profile updated successfully.'));
        }

        // For other user types, use the standard profile update request
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
