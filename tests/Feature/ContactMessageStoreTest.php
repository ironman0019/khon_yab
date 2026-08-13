<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_can_be_rendered(): void
    {
        $response = $this->get(route('home.contact'));

        $response->assertOk();
        $response->assertSee(__('home.Contact Us'), false);
        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="subject"', false);
        $response->assertSee('name="message"', false);
        $response->assertSee('https://github.com/ironman0019/khon_yab.git', false);
        $response->assertSee(__('home.GitHub'), false);
        $response->assertSee(__('home.View source on GitHub'), false);
    }

    public function test_guest_can_submit_contact_form(): void
    {
        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '09123456789',
            'subject' => 'Need help',
            'message' => 'Please contact me about blood donation.',
        ];

        $response = $this->post(route('home.contact.store'), $payload);

        $response->assertRedirect(route('home.contact'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '09123456789',
            'subject' => 'Need help',
            'is_read' => false,
        ]);

        $this->assertSame(1, ContactMessage::query()->unread()->count());
    }

    public function test_contact_form_requires_name_email_subject_and_message(): void
    {
        $response = $this->from(route('home.contact'))
            ->post(route('home.contact.store'), []);

        $response->assertRedirect(route('home.contact'));
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_github_source_translations_exist_in_persian_and_pashto(): void
    {
        /** @var array<string, string> $fa */
        $fa = require lang_path('fa/home.php');
        /** @var array<string, string> $ps */
        $ps = require lang_path('ps/home.php');

        $this->assertSame('گیت‌هاب', $fa['GitHub']);
        $this->assertSame('مشاهده منبع در گیت‌هاب', $fa['View source on GitHub']);
        $this->assertSame('ګیټ‌هب', $ps['GitHub']);
        $this->assertSame('په ګیټ‌هب کې سرچینه وګورئ', $ps['View source on GitHub']);
    }
}
