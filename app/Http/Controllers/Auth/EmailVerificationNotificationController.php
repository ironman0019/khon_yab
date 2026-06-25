<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('verification.notice')
                ->withErrors(['email' => __('We could not send the verification email. Please try again in a moment.')]);
        }

        return redirect()->route('verification.notice')
            ->with('status', 'verification-link-sent');
    }
}
