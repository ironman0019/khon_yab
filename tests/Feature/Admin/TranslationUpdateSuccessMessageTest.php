<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class TranslationUpdateSuccessMessageTest extends TestCase
{
    public function test_translation_updated_successfully_translation_exists_in_persian_lang_file(): void
    {
        /** @var array<string, string> $translations */
        $translations = require lang_path('fa/admin.php');

        $this->assertSame(
            'ترجمه با موفقیت به‌روزرسانی شد.',
            $translations['Translation updated successfully.']
        );
    }

    public function test_translation_updated_successfully_translation_exists_in_pashto_lang_file(): void
    {
        /** @var array<string, string> $translations */
        $translations = require lang_path('ps/admin.php');

        $this->assertSame(
            'ژباړه په بریالیتوب سره تازه شوه.',
            $translations['Translation updated successfully.']
        );
    }

    public function test_translation_controller_uses_translated_success_message_on_update(): void
    {
        $controllerSource = file_get_contents(
            app_path('Http/Controllers/Admin/LanguageManagement/TranslationController.php')
        );

        $this->assertNotFalse($controllerSource);
        $this->assertStringContainsString(
            "__('admin.Translation updated successfully.')",
            $controllerSource
        );
        $this->assertStringNotContainsString(
            "->with('success', 'Translation updated successfully.')",
            $controllerSource
        );
    }
}
