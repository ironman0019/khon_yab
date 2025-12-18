<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set locale from cookie if available
        $locale = $request->cookie('locale');

        // Validate locale and check if language is active
        if ($locale && in_array($locale, ['en', 'fa', 'ps'])) {
            $language = Language::where('code', $locale)
                ->where('is_active', true)
                ->first();

            if ($language) {
                App::setLocale($locale);
            } else {
                // Fallback to default language if cookie locale is inactive
                $defaultLanguage = Language::where('is_default', true)
                    ->where('is_active', true)
                    ->first();

                if ($defaultLanguage) {
                    App::setLocale($defaultLanguage->code);
                }
            }
        } else {
            // No cookie or invalid locale, use default language
            $defaultLanguage = Language::where('is_default', true)
                ->where('is_active', true)
                ->first();

            if ($defaultLanguage) {
                App::setLocale($defaultLanguage->code);
            }
        }

        return $next($request);
    }
}
