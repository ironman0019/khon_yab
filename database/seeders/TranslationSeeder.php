<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $langPath = base_path('lang');
        $languages = ['en', 'fa', 'ps'];

        foreach ($languages as $locale) {
            $localePath = $langPath.DIRECTORY_SEPARATOR.$locale;

            if (! File::exists($localePath)) {
                continue;
            }

            // Get all PHP files in the locale directory
            $files = File::glob($localePath.DIRECTORY_SEPARATOR.'*.php');

            foreach ($files as $file) {
                $group = basename($file, '.php');
                $translations = require $file;

                if (! is_array($translations)) {
                    continue;
                }

                // Check if language exists in database, create if not
                $language = Language::where('code', $locale)->first();
                if (! $language) {
                    $this->command->warn("Language with code '{$locale}' not found in database. Creating...");
                    $languageData = match ($locale) {
                        'en' => ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'direction' => 'ltr', 'is_active' => true, 'is_default' => true],
                        'fa' => ['code' => 'fa', 'name' => 'Persian', 'native_name' => 'فارسی', 'direction' => 'rtl', 'is_active' => true, 'is_default' => false],
                        'ps' => ['code' => 'ps', 'name' => 'Pashto', 'native_name' => 'پښتو', 'direction' => 'rtl', 'is_active' => true, 'is_default' => false],
                        default => null,
                    };

                    if ($languageData) {
                        $language = Language::create($languageData);
                        $this->command->info("Created language: {$locale}");
                    } else {
                        $this->command->warn("Unknown language code '{$locale}'. Skipping...");

                        continue;
                    }
                }

                // Flatten nested arrays using dot notation
                $flattened = $this->flattenTranslations($translations);

                foreach ($flattened as $key => $value) {
                    // Skip non-string values
                    if (! is_string($value)) {
                        continue;
                    }

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
                    }
                }

                $this->command->info("Imported translations from {$locale}/{$group}.php");
            }
        }

        // Clear all translation caches after importing
        // Get all unique groups from the translations table
        $groups = Translation::distinct()->pluck('group')->toArray();
        $languages = ['en', 'fa', 'ps'];

        foreach ($languages as $langCode) {
            foreach ($groups as $groupItem) {
                Cache::forget("translations.{$langCode}.{$groupItem}");
            }
        }

        $this->command->info('Translation import completed!');
        $this->command->info('Translation caches have been cleared.');
    }

    /**
     * Flatten nested translation arrays using dot notation.
     *
     * @param  array<string, mixed>  $array
     * @return array<string, string>
     */
    private function flattenTranslations(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenTranslations($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
