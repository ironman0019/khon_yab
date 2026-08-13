<?php

namespace Tests\Feature\Admin;

use App\Enums\UserType;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContactMessageNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        $admin = new User;
        $admin->full_name = 'Admin User';
        $admin->email = 'admin-notifications@example.com';
        $admin->password = Hash::make('password');
        $admin->user_type = UserType::Receiver->value;
        $admin->is_admin = true;
        $admin->email_verified_at = now();
        $admin->save();

        return $admin->fresh();
    }

    public function test_admin_notifications_include_unread_contact_messages(): void
    {
        $admin = $this->createAdmin();
        $unread = ContactMessage::factory()->unread()->create([
            'name' => 'Contact Sender',
            'subject' => 'Urgent question',
            'message' => 'I need help with donation scheduling.',
        ]);
        ContactMessage::factory()->read()->create([
            'name' => 'Already Read',
            'subject' => 'Old subject',
        ]);

        $response = $this->actingAs($admin)
            ->getJson(route('admin.notifications'));

        $response->assertOk();
        $response->assertJsonPath('count', 1);
        $response->assertJsonFragment([
            'id' => 'contact_message_'.$unread->id,
            'type' => 'contact_message',
            'sender_name' => 'Contact Sender',
            'subject' => 'Urgent question',
        ]);
        $response->assertJsonMissing([
            'sender_name' => 'Already Read',
        ]);
    }

    public function test_admin_notification_count_combines_blood_requests_and_contacts(): void
    {
        $admin = $this->createAdmin();
        ContactMessage::factory()->unread()->count(2)->create();

        $response = $this->actingAs($admin)
            ->getJson(route('admin.notifications'));

        $response->assertOk();
        $response->assertJsonPath('count', 2);
        $this->assertCount(2, $response->json('notifications'));
    }
}
