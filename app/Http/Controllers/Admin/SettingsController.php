<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): View
    {
        $defaultLanguage = Setting::get('default_language_code', config('app.locale', 'en'));
        $currentLocale = app()->getLocale();

        // Helper function to get setting value, handling JSON multilingual settings
        $getSettingValue = function (string $key, mixed $default = null) use ($defaultLanguage, $currentLocale): mixed {
            $setting = Setting::where('key', $key)->first();

            if (! $setting) {
                return $default;
            }

            // If it's a JSON type, extract the value for current locale or default language
            if (in_array($setting->type, ['json', 'array'])) {
                $value = json_decode($setting->value, true);
                if (is_array($value)) {
                    // Try current locale first, then default language, then first available value
                    return $value[$currentLocale] ?? $value[$defaultLanguage] ?? ($value['en'] ?? reset($value) ?? $default);
                }

                return $default;
            }

            // For non-JSON settings, use the Setting::get method
            return Setting::get($key, $default);
        };

        $settings = [
            'site_name' => $getSettingValue('site_name', config('app.name')),
            'site_logo' => $getSettingValue('site_logo'),
            'default_language_code' => $getSettingValue('default_language_code'),
            'site_email' => $getSettingValue('site_email'),
            'site_phone' => $getSettingValue('site_phone'),
            'site_address' => $getSettingValue('site_address'),
        ];

        $languages = Language::where('is_active', true)->get();

        return view('admin.settings.index', compact('settings', 'languages'));
    }

    /**
     * Update the settings.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle site name
        if ($request->has('site_name')) {
            Setting::set('site_name', $validated['site_name'], 'string');
        }

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::exists($oldLogo)) {
                Storage::delete($oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath, 'string');
        }

        // Handle default language
        if ($request->has('default_language_code')) {
            Setting::set('default_language_code', $validated['default_language_code'], 'string');

            // Also update the language model if exists
            if ($validated['default_language_code']) {
                Language::where('code', $validated['default_language_code'])->update(['is_default' => true]);
                Language::where('code', '!=', $validated['default_language_code'])->update(['is_default' => false]);
            }
        }

        // Handle other settings
        if ($request->has('site_email')) {
            Setting::set('site_email', $validated['site_email'], 'string');
        }

        if ($request->has('site_phone')) {
            Setting::set('site_phone', $validated['site_phone'], 'string');
        }

        if ($request->has('site_address')) {
            Setting::set('site_address', $validated['site_address'], 'string');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', __('admin.Settings updated successfully.'));
    }
}
