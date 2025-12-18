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
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_logo' => Setting::get('site_logo'),
            'default_language_code' => Setting::get('default_language_code'),
            'site_email' => Setting::get('site_email'),
            'site_phone' => Setting::get('site_phone'),
            'site_address' => Setting::get('site_address'),
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
            ->with('success', 'Settings updated successfully.');
    }
}
