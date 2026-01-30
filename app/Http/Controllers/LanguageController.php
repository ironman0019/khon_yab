<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch application language.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        // Validate locale
        if (! in_array($locale, ['en', 'fa', 'ps'])) {
            $locale = 'en';
        }

        // Set locale for current request
        App::setLocale($locale);

        // Store in session so the redirected request (same page) uses it immediately
        $request->session()->put('locale', $locale);

        // Also set cookie for subsequent requests and cross-session persistence
        return redirect()->back()
            ->with('locale_changed', true)
            ->cookie('locale', $locale, 525600); // 1 year expiration
    }
}
