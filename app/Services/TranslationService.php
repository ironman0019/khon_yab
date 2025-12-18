<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Translation\FileLoader;

class TranslationService
{
    public function __construct(
        protected FileLoader $fileLoader
    ) {}

    /**
     * Load translations from database for a given locale and group.
     */
    public function loadTranslations(string $locale, string $group): array
    {
        $cacheKey = "translations.{$locale}.{$group}";

        return Cache::remember($cacheKey, 3600, function () use ($locale, $group) {
            // Load database translations
            $dbTranslations = Translation::where('language_code', $locale)
                ->where('group', $group)
                ->get()
                ->pluck('value', 'key')
                ->toArray();

            // Load file translations as fallback using the original file loader directly
            // This avoids infinite loop by not going through Lang::getLoader()
            $fileTranslations = $this->fileLoader->load($locale, $group);
            if (! is_array($fileTranslations)) {
                $fileTranslations = [];
            }

            // Merge: database translations override file translations (database takes priority)
            return array_merge($fileTranslations, $dbTranslations);
        });
    }

    /**
     * Load a single translation key from database.
     */
    public function getTranslation(string $locale, string $group, string $key): ?string
    {
        $translation = Translation::where('language_code', $locale)
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        if ($translation) {
            return $translation->value;
        }

        // Fallback to lang files
        return Lang::get("{$group}.{$key}", [], $locale);
    }

    /**
     * Clear translation cache for a specific locale and group.
     */
    public function clearCache(?string $locale = null, ?string $group = null): void
    {
        if ($locale && $group) {
            Cache::forget("translations.{$locale}.{$group}");
        } elseif ($locale) {
            $groups = Translation::where('language_code', $locale)
                ->distinct()
                ->pluck('group')
                ->toArray();

            foreach ($groups as $groupItem) {
                Cache::forget("translations.{$locale}.{$groupItem}");
            }
        } else {
            // Clear all translation caches
            $languages = Language::where('is_active', true)->pluck('code');
            foreach ($languages as $langCode) {
                $groups = Translation::where('language_code', $langCode)
                    ->distinct()
                    ->pluck('group')
                    ->toArray();

                foreach ($groups as $groupItem) {
                    Cache::forget("translations.{$langCode}.{$groupItem}");
                }
            }
        }
    }

    /**
     * Get all available translation groups.
     */
    public function getGroups(): array
    {
        return Translation::distinct()
            ->orderBy('group')
            ->pluck('group')
            ->toArray();
    }

    /**
     * Get all translation keys for a specific group.
     */
    public function getKeysForGroup(string $group): array
    {
        return Translation::where('group', $group)
            ->distinct()
            ->orderBy('key')
            ->pluck('key')
            ->toArray();
    }
}
