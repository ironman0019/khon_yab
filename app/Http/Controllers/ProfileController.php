<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Controllers\Laboratory\ProfileController as LaboratoryProfileController;
use App\Http\Requests\Laboratory\UpdateLaboratoryProfileRequest;
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

        // If user is a laboratory user, delegate to laboratory profile controller
        if ($user->user_type === UserType::Laboratory->value) {
            $laboratoryController = new LaboratoryProfileController;

            return $laboratoryController->edit('profile.update');
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

        // If user is a laboratory user, validate and update using laboratory profile request
        if ($user->user_type === UserType::Laboratory->value) {
            $formRequest = new UpdateLaboratoryProfileRequest;
            $validated = $request->validate($formRequest->rules(), $formRequest->messages());
            $laboratory = $user->laboratory;

            if (! $laboratory) {
                abort(404, __('laboratory.Laboratory profile not found.'));
            }

            $laboratory->update($validated);

            return Redirect::route('profile.edit')
                ->with('success', __('laboratory.Laboratory profile updated successfully.'));
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
