<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminMessageStoreTest extends TestCase
{
    public function test_message_sent_successfully_translation_exists_in_persian_lang_file(): void
    {
        /** @var array<string, string> $translations */
        $translations = require lang_path('fa/admin.php');

        $this->assertSame(
            'پیام با موفقیت ارسال شد.',
            $translations['Message sent successfully.']
        );
    }

    public function test_message_sent_successfully_translation_exists_in_pashto_lang_file(): void
    {
        /** @var array<string, string> $translations */
        $translations = require lang_path('ps/admin.php');

        $this->assertSame(
            'پیغام په بریالیتوب سره واستول شو.',
            $translations['Message sent successfully.']
        );
    }
}
