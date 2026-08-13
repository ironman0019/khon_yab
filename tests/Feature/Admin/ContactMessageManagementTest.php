<?php

namespace Tests\Feature\Admin;

use App\Enums\UserType;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContactMessageManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        $admin = new User;
        $admin->full_name = 'Admin User';
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password');
        $admin->user_type = UserType::Receiver->value;
        $admin->is_admin = true;
        $admin->email_verified_at = now();
        $admin->save();

        return $admin->fresh();
    }

    private function createRegularUser(): User
    {
        $user = new User;
        $user->full_name = 'Regular User';
        $user->email = 'user@example.com';
        $user->password = Hash::make('password');
        $user->user_type = UserType::Receiver->value;
        $user->is_admin = false;
        $user->email_verified_at = now();
        $user->save();

        return $user->fresh();
    }

    public function test_admin_can_view_contact_messages_index(): void
    {
        $admin = $this->createAdmin();
        ContactMessage::factory()->create([
            'name' => 'Alice',
            'subject' => 'Hello admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.contact-message-management.index'));

        $response->assertOk();
        $response->assertSee('Alice', false);
        $response->assertSee('Hello admin', false);
    }

    public function test_admin_can_search_contact_messages(): void
    {
        $admin = $this->createAdmin();
        ContactMessage::factory()->create([
            'name' => 'Searchable Person',
            'email' => 'searchable@example.com',
            'subject' => 'Unique subject',
        ]);
        ContactMessage::factory()->create([
            'name' => 'Other Person',
            'email' => 'other@example.com',
            'subject' => 'Different subject',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.contact-message-management.index', [
                'search' => 'Searchable',
            ]));

        $response->assertOk();
        $response->assertSee('Searchable Person', false);
        $response->assertDontSee('Other Person', false);
    }

    public function test_admin_can_filter_unread_contact_messages(): void
    {
        $admin = $this->createAdmin();
        ContactMessage::factory()->unread()->create(['name' => 'Unread Sender']);
        ContactMessage::factory()->read()->create(['name' => 'Read Sender']);

        $response = $this->actingAs($admin)
            ->get(route('admin.contact-message-management.index', [
                'status' => 'unread',
            ]));

        $response->assertOk();
        $response->assertSee('Unread Sender', false);
        $response->assertDontSee('Read Sender', false);
    }

    public function test_viewing_contact_message_marks_it_as_read(): void
    {
        $admin = $this->createAdmin();
        $message = ContactMessage::factory()->unread()->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.contact-message-management.show', $message));

        $response->assertOk();
        $this->assertTrue($message->fresh()->is_read);
        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_admin_can_mark_contact_message_as_unread(): void
    {
        $admin = $this->createAdmin();
        $message = ContactMessage::factory()->read()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.contact-message-management.unread', $message));

        $response->assertRedirect(route('admin.contact-message-management.show', $message));
        $this->assertFalse($message->fresh()->is_read);
        $this->assertNull($message->fresh()->read_at);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $admin = $this->createAdmin();
        $message = ContactMessage::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('admin.contact-message-management.destroy', $message));

        $response->assertRedirect(route('admin.contact-message-management.index'));
        $this->assertSoftDeleted($message);
    }

    public function test_non_admin_cannot_access_contact_messages(): void
    {
        $user = $this->createRegularUser();
        $message = ContactMessage::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.contact-message-management.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.contact-message-management.show', $message))
            ->assertForbidden();
    }
}
