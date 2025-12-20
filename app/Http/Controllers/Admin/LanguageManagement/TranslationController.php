<?php

namespace App\Http\Controllers\Admin\LanguageManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageManagement\StoreTranslationRequest;
use App\Http\Requests\Admin\LanguageManagement\UpdateTranslationRequest;
use App\Models\Language;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TranslationController extends Controller
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    /**
     * Display a listing of translations.
     */
    public function index(Request $request, ?Language $language = null): View
    {
        $query = Translation::query();

        // Filter by language - prioritize query parameter over route parameter
        if ($request->filled('language_code')) {
            $query->where('language_code', $request->get('language_code'));
        } elseif ($language) {
            // Use route parameter as default if no query parameter provided
            $query->where('language_code', $language->code);
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->get('group'));
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }

        $translations = $query->with('language')->latest()->paginate(20)->withQueryString();
        $languages = Language::where('is_active', true)->orderBy('name')->get();
        $groups = $this->translationService->getGroups();

        return view('admin.language-management.translations.index', compact('translations', 'languages', 'groups', 'language'));
    }

    /**
     * Show the form for creating a new translation.
     */
    public function create(?Language $language = null): View
    {
        $languages = Language::where('is_active', true)->orderBy('name')->get();
        $groups = $this->translationService->getGroups();

        return view('admin.language-management.translations.create', compact('languages', 'groups', 'language'));
    }

    /**
     * Store a newly created translation.
     */
    public function store(StoreTranslationRequest $request, ?Language $language = null): RedirectResponse
    {
        // Check if translation already exists
        $existing = Translation::where('key', $request->key)
            ->where('group', $request->group)
            ->where('language_code', $request->language_code)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Translation already exists for this key, group, and language combination.');
        }

        Translation::create($request->validated());

        // Clear cache for this translation
        $this->translationService->clearCache($request->language_code, $request->group);

        $redirectLanguage = $language ?? Language::where('code', $request->language_code)->first();

        return redirect()->route('admin.language-management.translations.index', $redirectLanguage)
            ->with('success', 'Translation created successfully.');
    }

    /**
     * Display the specified translation.
     */
    public function show(?Language $language, Translation $translation): View
    {
        $translation->load('language');

        return view('admin.language-management.translations.show', compact('translation', 'language'));
    }

    /**
     * Show the form for editing the specified translation.
     */
    public function edit(?Language $language, Translation $translation): View
    {
        $languages = Language::where('is_active', true)->orderBy('name')->get();
        $groups = $this->translationService->getGroups();

        return view('admin.language-management.translations.edit', compact('translation', 'languages', 'groups', 'language'));
    }

    /**
     * Update the specified translation.
     */
    public function update(UpdateTranslationRequest $request, ?Language $language, Translation $translation): RedirectResponse
    {
        $oldLanguageCode = $translation->language_code;
        $oldGroup = $translation->group;

        // Check if another translation with same key, group, and language_code exists
        $existing = Translation::where('key', $request->key)
            ->where('group', $request->group)
            ->where('language_code', $request->language_code)
            ->where('id', '!=', $translation->id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Translation already exists for this key, group, and language combination.');
        }

        $translation->update($request->validated());

        // Clear cache for old and new translation locations
        $this->translationService->clearCache($oldLanguageCode, $oldGroup);
        if ($oldLanguageCode !== $request->language_code || $oldGroup !== $request->group) {
            $this->translationService->clearCache($request->language_code, $request->group);
        }

        $redirectLanguage = $language ?? Language::where('code', $request->language_code)->first();

        return redirect()->route('admin.language-management.translations.index', $redirectLanguage)
            ->with('success', 'Translation updated successfully.');
    }

    /**
     * Remove the specified translation.
     */
    public function destroy(?Language $language, Translation $translation): RedirectResponse
    {
        $languageCode = $translation->language_code;
        $group = $translation->group;

        $translation->delete();

        // Clear cache
        $this->translationService->clearCache($languageCode, $group);

        $redirectLanguage = $language ?? Language::where('code', $languageCode)->first();

        return redirect()->route('admin.language-management.translations.index', $redirectLanguage)
            ->with('success', 'Translation deleted successfully.');
    }

    /**
     * Import translations from lang files to database.
     */
    public function importFromFiles(Request $request): RedirectResponse
    {
        $request->validate([
            'language_code' => ['required', 'string', 'exists:languages,code'],
            'group' => ['required', 'string'],
        ]);

        $locale = $request->language_code;
        $group = $request->group;

        // Load translations from lang files
        $fileTranslations = lang($group, [], $locale);

        if (! is_array($fileTranslations)) {
            return redirect()->back()
                ->with('error', 'No translations found in lang files for the specified group and language.');
        }

        $imported = 0;
        $skipped = 0;

        foreach ($fileTranslations as $key => $value) {
            // Check if translation already exists
            $existing = Translation::where('key', $key)
                ->where('group', $group)
                ->where('language_code', $locale)
                ->first();

            if (! $existing) {
                Translation::create([
                    'key' => $key,
                    'group' => $group,
                    'language_code' => $locale,
                    'value' => $value,
                ]);
                $imported++;
            } else {
                $skipped++;
            }
        }

        // Clear cache
        $this->translationService->clearCache($locale, $group);

        return redirect()->back()
            ->with('success', "Import completed. {$imported} translations imported, {$skipped} skipped (already exist).");
    }
}
