<?php

namespace Tests\Feature;

use App\Enums\UserType;
use App\Models\Message;
use App\Models\User;
use Database\Seeders\MessageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MessageSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_seeder_sends_random_messages_and_guarantees_admin_messages(): void
    {
        $admin = User::query()->create([
            'full_name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Receiver->value,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $regularUserOne = User::query()->create([
            'full_name' => 'Regular User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Receiver->value,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $regularUserTwo = User::query()->create([
            'full_name' => 'Regular User Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Donor->value,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->seed(MessageSeeder::class);

        $this->assertSame(40, Message::count());
        $this->assertGreaterThanOrEqual(10, Message::query()->where('recipient_id', $admin->id)->count());
        $this->assertTrue(
            Message::query()
                ->whereIn('recipient_id', [$regularUserOne->id, $regularUserTwo->id])
                ->exists()
        );
        $this->assertSame(0, Message::query()->where('sender_id', $admin->id)->where('recipient_id', $admin->id)->count());
    }

    public function test_message_seeder_still_creates_random_messages_without_admin_users(): void
    {
        User::query()->create([
            'full_name' => 'User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Receiver->value,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        User::query()->create([
            'full_name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Receiver->value,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->seed(MessageSeeder::class);

        $this->assertSame(30, Message::count());
    }
}
