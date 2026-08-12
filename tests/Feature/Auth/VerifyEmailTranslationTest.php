<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class VerifyEmailTranslationTest extends TestCase
{
    public function test_verify_email_translations_exist_in_persian_lang_file(): void
    {
        /** @var array<string, string> $translations */
        $translations = require lang_path('fa/auth.php');

        $this->assertSame(
            'از ثبت نام شما متشکریم! قبل از شروع، لطفاً آدرس ایمیل خود را با کلیک روی لینکی که برای شما ایمیل کرده‌ایم تأیید کنید. اگر ایمیل را دریافت نکرده‌اید، با کمال میل یکی دیگر برای شما ارسال می‌کنیم.',
            $translations['Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.']
        );
        $this->assertSame(
            'لینک تأیید جدید به آدرس ایمیل ارائه شده در زمان ثبت نام ارسال شد.',
            $translations['A new verification link has been sent to the email address you provided during registration.']
        );
        $this->assertSame('ارسال مجدد ایمیل تأیید', $translations['Resend Verification Email']);
        $this->assertSame('خروج', $translations['Log Out']);
    }

    public function test_verify_email_translations_exist_in_pashto_lang_file(): void
    {
        /** @var array<string, string> $translations */
        $translations = require lang_path('ps/auth.php');

        $this->assertSame(
            'د نوم لیکنې لپاره مننه! پیل کولو دمخه، مهرباني وکړئ خپل بریښنالیک پته د هغه لینک په کلیک کولو سره تایید کړئ چې موږ یې تاسو ته ولیږل. که تاسو بریښنالیک ترلاسه نه کړئ، موږ به په خوښۍ بل یو وړاندې کړو.',
            $translations['Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.']
        );
        $this->assertSame(
            'نوی تایید لینک هغه بریښنالیک پتې ته لیږل شوی چې تاسو یې د نوم لیکنې پر مهال چمتو کړی و.',
            $translations['A new verification link has been sent to the email address you provided during registration.']
        );
        $this->assertSame('تایید بریښنالیک بیا ولیږئ', $translations['Resend Verification Email']);
        $this->assertSame('وتل', $translations['Log Out']);
    }

    public function test_verify_email_view_uses_auth_translation_namespace(): void
    {
        $view = file_get_contents(resource_path('views/auth/verify-email.blade.php'));

        $this->assertNotFalse($view);
        $this->assertStringContainsString("__('auth.Thanks for signing up!", $view);
        $this->assertStringContainsString("__('auth.A new verification link has been sent to the email address you provided during registration.')", $view);
        $this->assertStringContainsString("__('auth.Resend Verification Email')", $view);
        $this->assertStringContainsString("__('auth.Log Out')", $view);
        $this->assertStringNotContainsString("{{ __('Thanks for signing up!", $view);
        $this->assertStringNotContainsString("{{ __('Resend Verification Email') }}", $view);
    }
}
