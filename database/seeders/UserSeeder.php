<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\City;
use App\Models\Donor;
use App\Models\Laboratory;
use App\Models\Province;
use App\Models\Receiver;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $province = Province::query()->with('cities')->whereHas('cities')->first();
        $city = $province?->cities->first();

        if (! $province || ! $city) {
            $this->command->warn('No provinces/cities found. Receiver, donor, and laboratory profiles will be skipped.');
        }

        // Admin users with multilingual names
        $adminUsers = [
            [
                'full_name' => 'Admin User',
                'email' => 'admin@khonyab.ir',
            ],
            [
                'full_name' => 'مدیر سیستم',
                'email' => 'admin-fa@khonyab.ir',
            ],
            [
                'full_name' => 'د سیسټم مدیر',
                'email' => 'admin-ps@khonyab.ir',
            ],
        ];

        foreach ($adminUsers as $userData) {
            $this->createVerifiedUser([
                ...$userData,
                'user_type' => UserType::Receiver->value,
                'is_admin' => true,
            ]);
        }

        // Receiver users (user_type = 0)
        $receivers = [
            [
                'full_name' => 'John Smith',
                'email' => 'user1@example.com',
                'mobile_number' => '09120000001',
                'national_code' => '9100000001',
            ],
            [
                'full_name' => 'احمد محمدی',
                'email' => 'user2@example.com',
                'mobile_number' => '09120000002',
                'national_code' => '9100000002',
            ],
            [
                'full_name' => 'احمد خان',
                'email' => 'user3@example.com',
                'mobile_number' => '09120000003',
                'national_code' => '9100000003',
            ],
            [
                'full_name' => 'Receiver User',
                'email' => 'receiver@khonyab.ir',
                'mobile_number' => '09120000004',
                'national_code' => '9100000004',
            ],
            [
                'full_name' => 'کاربر گیرنده',
                'email' => 'receiver-fa@khonyab.ir',
                'mobile_number' => '09120000005',
                'national_code' => '9100000005',
            ],
            [
                'full_name' => 'د ترلاسه کوونکي کارن',
                'email' => 'receiver-ps@khonyab.ir',
                'mobile_number' => '09120000006',
                'national_code' => '9100000006',
            ],
        ];

        foreach ($receivers as $receiverData) {
            $user = $this->createVerifiedUser([
                'full_name' => $receiverData['full_name'],
                'email' => $receiverData['email'],
                'user_type' => UserType::Receiver->value,
                'is_admin' => false,
            ]);

            $this->createReceiverProfile($user, $receiverData, $province, $city);
        }

        // Donor users (user_type = 1)
        $donors = [
            [
                'full_name' => 'Donor User',
                'email' => 'donor@khonyab.ir',
                'mobile_number' => '09121000001',
                'national_code' => '9200000001',
            ],
            [
                'full_name' => 'کاربر اهداکننده',
                'email' => 'donor-fa@khonyab.ir',
                'mobile_number' => '09121000002',
                'national_code' => '9200000002',
            ],
            [
                'full_name' => 'د ورکوونکي کارن',
                'email' => 'donor-ps@khonyab.ir',
                'mobile_number' => '09121000003',
                'national_code' => '9200000003',
            ],
        ];

        foreach ($donors as $donorData) {
            $user = $this->createVerifiedUser([
                'full_name' => $donorData['full_name'],
                'email' => $donorData['email'],
                'user_type' => UserType::Donor->value,
                'is_admin' => false,
            ]);

            $this->createDonorProfile($user, $donorData, $province, $city);
        }

        // Laboratory users (user_type = 2)
        $laboratories = [
            [
                'full_name' => 'Laboratory User',
                'email' => 'laboratory@khonyab.ir',
                'laboratory_name' => 'KhonYab Central Laboratory',
                'laboratory_code' => 'USER-LAB-001',
                'mobile_number' => '09122000001',
                'license_number' => 'LIC-USER-001',
            ],
            [
                'full_name' => 'کاربر آزمایشگاه',
                'email' => 'laboratory-fa@khonyab.ir',
                'laboratory_name' => 'آزمایشگاه مرکزی خون‌یاب',
                'laboratory_code' => 'USER-LAB-002',
                'mobile_number' => '09122000002',
                'license_number' => 'LIC-USER-002',
            ],
            [
                'full_name' => 'د لابراتوار کارن',
                'email' => 'laboratory-ps@khonyab.ir',
                'laboratory_name' => 'د خون‌یاب مرکزي لابراتوار',
                'laboratory_code' => 'USER-LAB-003',
                'mobile_number' => '09122000003',
                'license_number' => 'LIC-USER-003',
            ],
        ];

        foreach ($laboratories as $laboratoryData) {
            $user = $this->createVerifiedUser([
                'full_name' => $laboratoryData['full_name'],
                'email' => $laboratoryData['email'],
                'user_type' => UserType::Laboratory->value,
                'is_admin' => false,
            ]);

            $this->createLaboratoryProfile($user, $laboratoryData, $province, $city);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Total users: '.User::count());
        $this->command->info('Receivers: '.User::query()->where('user_type', UserType::Receiver->value)->where('is_admin', false)->count());
        $this->command->info('Donors: '.User::query()->where('user_type', UserType::Donor->value)->count());
        $this->command->info('Laboratories: '.User::query()->where('user_type', UserType::Laboratory->value)->count());
    }

    /**
     * Create or update a verified user account.
     *
     * @param  array{full_name: string, email: string, user_type: int, is_admin: bool}  $attributes
     */
    private function createVerifiedUser(array $attributes): User
    {
        $user = User::updateOrCreate(
            ['email' => $attributes['email']],
            [
                'full_name' => $attributes['full_name'],
                'email' => $attributes['email'],
                'password' => Hash::make('password'),
                'user_type' => $attributes['user_type'],
                'is_admin' => $attributes['is_admin'],
            ]
        );

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user->fresh();
    }

    /**
     * @param  array{mobile_number: string, national_code: string}  $receiverData
     */
    private function createReceiverProfile(User $user, array $receiverData, ?Province $province, ?City $city): void
    {
        if (! $province || ! $city) {
            return;
        }

        Receiver::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'mobile_number' => $receiverData['mobile_number'],
                'national_code' => $receiverData['national_code'],
                'age' => 30,
                'gender' => 'male',
                'province_id' => $province->id,
                'city_id' => $city->id,
                'address' => 'Seeded receiver address',
                'blood_type' => 'O',
                'rh_factor' => 'positive',
            ]
        );
    }

    /**
     * @param  array{mobile_number: string, national_code: string}  $donorData
     */
    private function createDonorProfile(User $user, array $donorData, ?Province $province, ?City $city): void
    {
        if (! $province || ! $city) {
            return;
        }

        Donor::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'mobile_number' => $donorData['mobile_number'],
                'national_code' => $donorData['national_code'],
                'age' => 28,
                'gender' => 'male',
                'province_id' => $province->id,
                'city_id' => $city->id,
                'address' => 'Seeded donor address',
                'blood_type' => 'A',
                'rh_factor' => 'positive',
                'health_status' => true,
                'ability_to_donate' => true,
                'last_donation_date' => now()->subDays(90),
            ]
        );
    }

    /**
     * @param  array{laboratory_name: string, laboratory_code: string, mobile_number: string, license_number: string, full_name: string}  $laboratoryData
     */
    private function createLaboratoryProfile(User $user, array $laboratoryData, ?Province $province, ?City $city): void
    {
        if (! $province || ! $city) {
            return;
        }

        Laboratory::updateOrCreate(
            ['laboratory_code' => $laboratoryData['laboratory_code']],
            [
                'user_id' => $user->id,
                'laboratory_name' => $laboratoryData['laboratory_name'],
                'laboratory_code' => $laboratoryData['laboratory_code'],
                'mobile_number' => $laboratoryData['mobile_number'],
                'phone_number' => '0210000000',
                'province_id' => $province->id,
                'city_id' => $city->id,
                'address' => 'Seeded laboratory address',
                'license_number' => $laboratoryData['license_number'],
                'contact_person_name' => $laboratoryData['full_name'],
                'status' => 3, // verified
            ]
        );
    }
}
