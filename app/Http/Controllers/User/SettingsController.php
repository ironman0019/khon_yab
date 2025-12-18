<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserSettingsRequest;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display user settings page.
     */
    public function index(): View
    {
        $user = auth()->user();
        $languages = Language::where('is_active', true)->get();

        // Get user preferences (stored in user_meta or settings table)
        // For now, we'll use a simple approach with user settings
        $settings = [
            'language_code' => $this->getUserSetting($user, 'language_code'),
            'notifications_email' => $this->getUserSetting($user, 'notifications_email', true),
            'notifications_sms' => $this->getUserSetting($user, 'notifications_sms', false),
        ];

        return view('user.settings.index', compact('settings', 'languages'));
    }

    /**
     * Update user settings.
     */
    public function update(UpdateUserSettingsRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Store settings (you might want to create a user_settings table or use JSON column)
        // For now, using a simple key-value approach in a separate table or JSON
        foreach ($validated as $key => $value) {
            $this->setUserSetting($user, $key, $value);
        }

        return redirect()->route('user.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Get user setting value.
     */
    protected function getUserSetting(User $user, string $key, mixed $default = null): mixed
    {
        // This is a placeholder - implement based on your storage strategy
        // You could use a user_settings table, JSON column, or cache
        return $default;
    }

    /**
     * Set user setting value.
     */
    protected function setUserSetting(User $user, string $key, mixed $value): void
    {
        // This is a placeholder - implement based on your storage strategy
        // You could use a user_settings table, JSON column, or cache
    }
}
