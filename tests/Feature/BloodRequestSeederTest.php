<?php

namespace Tests\Feature;

use App\Enums\UserType;
use App\Models\BloodRequest;
use App\Models\City;
use App\Models\Laboratory;
use App\Models\Province;
use App\Models\User;
use Database\Seeders\BloodRequestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BloodRequestSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_blood_request_seeder_creates_at_least_ten_requests_for_laboratory_users(): void
    {
        $admin = User::query()->create([
            'full_name' => 'مدیر سیستم',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Receiver->value,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $laboratoryUser = User::query()->create([
            'full_name' => 'دکتر علی احمدی',
            'email' => 'laboratory@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Laboratory->value,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $province = Province::query()->create(['name' => 'کابل']);
        $city = City::query()->create([
            'province_id' => $province->id,
            'name' => 'کابل',
        ]);

        Laboratory::query()->create([
            'user_id' => $laboratoryUser->id,
            'laboratory_name' => 'آزمایشگاه مرکزی کابل',
            'laboratory_code' => 'KCL-TEST-001',
            'mobile_number' => '0700123456',
            'phone_number' => '0201234567',
            'province_id' => $province->id,
            'city_id' => $city->id,
            'address' => 'منطقه مرکزی، کابل',
            'license_number' => 'LIC-TEST-001',
            'contact_person_name' => 'دکتر علی احمدی',
            'status' => 1,
        ]);

        $this->seed(BloodRequestSeeder::class);

        $this->assertGreaterThanOrEqual(10, BloodRequest::count());

        BloodRequest::query()->each(function (BloodRequest $bloodRequest) use ($admin, $laboratoryUser): void {
            $this->assertSame($laboratoryUser->id, $bloodRequest->requested_by);
            $this->assertMatchesRegularExpression('/^[\x{0600}-\x{06FF}\s‌]+$/u', $bloodRequest->patient_name);
            $this->assertMatchesRegularExpression('/^09[0-9]{9}$/', $bloodRequest->contact_number);
            $this->assertNotEmpty($bloodRequest->request_reason);
            $this->assertNotEmpty($bloodRequest->medical_center);
            $this->assertNotEmpty($bloodRequest->notes);

            if ($bloodRequest->approved_by !== null) {
                $this->assertSame($admin->id, $bloodRequest->approved_by);
            }
        });
    }

    public function test_blood_request_seeder_does_nothing_without_laboratory_users(): void
    {
        User::query()->create([
            'full_name' => 'مدیر سیستم',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'user_type' => UserType::Receiver->value,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $province = Province::query()->create(['name' => 'کابل']);
        City::query()->create([
            'province_id' => $province->id,
            'name' => 'کابل',
        ]);

        $this->seed(BloodRequestSeeder::class);

        $this->assertSame(0, BloodRequest::count());
    }
}
