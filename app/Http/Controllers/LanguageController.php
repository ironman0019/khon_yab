<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

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

        // Redirect back or to home with cookie
        return redirect()->back()
            ->with('locale_changed', true)
            ->cookie('locale', $locale, 525600); // 1 year expiration
    }
}
