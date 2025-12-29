<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\City;
use App\Models\Laboratory;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = Province::with('cities')->take(10)->get();

        if ($provinces->isEmpty()) {
            $this->command->warn('No provinces found. Please run AfghanistanProvinceCitySeeder first.');

            return;
        }

        // Laboratories with English names
        $englishLaboratories = [
            [
                'laboratory_name' => 'Kabul Central Laboratory',
                'laboratory_code' => 'KCL001',
                'contact_person_name' => 'Dr. John Anderson',
                'mobile_number' => '0700200001',
                'phone_number' => '0201234567',
                'license_number' => 'LIC-EN-001',
                'address' => 'Central District, Kabul, Afghanistan',
            ],
            [
                'laboratory_name' => 'Herat Medical Laboratory',
                'laboratory_code' => 'HML001',
                'contact_person_name' => 'Dr. Sarah Miller',
                'mobile_number' => '0700200002',
                'phone_number' => '0401234567',
                'license_number' => 'LIC-EN-002',
                'address' => 'Main Street, Herat City',
            ],
            [
                'laboratory_name' => 'Kandahar Regional Laboratory',
                'laboratory_code' => 'KRL001',
                'contact_person_name' => 'Dr. Michael Brown',
                'mobile_number' => '0700200003',
                'phone_number' => '0301234567',
                'license_number' => 'LIC-EN-003',
                'address' => 'Regional Medical District, Kandahar',
            ],
        ];

        // Laboratories with Persian names
        $persianLaboratories = [
            [
                'laboratory_name' => 'آزمایشگاه مرکزی کابل',
                'laboratory_code' => 'KCL002',
                'contact_person_name' => 'دکتر علی احمدی',
                'mobile_number' => '0700200004',
                'phone_number' => '0201234568',
                'license_number' => 'LIC-FA-001',
                'address' => 'منطقه مرکزی، کابل، افغانستان',
            ],
            [
                'laboratory_name' => 'آزمایشگاه درمانی هرات',
                'laboratory_code' => 'HML002',
                'contact_person_name' => 'دکتر فاطمه کریمی',
                'mobile_number' => '0700200005',
                'phone_number' => '0401234568',
                'license_number' => 'LIC-FA-002',
                'address' => 'خیابان اصلی، شهر هرات',
            ],
            [
                'laboratory_name' => 'آزمایشگاه مزار شریف',
                'laboratory_code' => 'MSL001',
                'contact_person_name' => 'دکتر محمد رضایی',
                'mobile_number' => '0700200006',
                'phone_number' => '0501234567',
                'license_number' => 'LIC-FA-003',
                'address' => 'بلخ، مزار شریف',
            ],
        ];

        // Laboratories with Pashto names
        $pashtoLaboratories = [
            [
                'laboratory_name' => 'د کابل مرکزي آزمايښت',
                'laboratory_code' => 'KCL003',
                'contact_person_name' => 'ډاکټر احمد خان',
                'mobile_number' => '0700200007',
                'phone_number' => '0201234569',
                'license_number' => 'LIC-PS-001',
                'address' => 'د کابل مرکزي سیمه',
            ],
            [
                'laboratory_name' => 'د جلال آباد آزمايښت',
                'laboratory_code' => 'JAL001',
                'contact_person_name' => 'ډاکټر زینب احمد',
                'mobile_number' => '0700200008',
                'phone_number' => '0601234567',
                'license_number' => 'LIC-PS-002',
                'address' => 'د ننگرهار، جلال آباد',
            ],
            [
                'laboratory_name' => 'د کندهار آزمايښت',
                'laboratory_code' => 'KAL001',
                'contact_person_name' => 'ډاکټر عمر گل',
                'mobile_number' => '0700200009',
                'phone_number' => '0301234568',
                'license_number' => 'LIC-PS-003',
                'address' => 'د کندهار مرکزي سړک',
            ],
        ];

        $allLaboratories = array_merge($englishLaboratories, $persianLaboratories, $pashtoLaboratories);
        $statuses = [0, 1, 2, 3]; // pending, active, inactive, verified

        foreach ($allLaboratories as $index => $laboratoryData) {
            // Get a random province and city
            $province = $provinces->random();
            $city = $province->cities->random();

            // Create user for laboratory
            $user = User::updateOrCreate(
                ['email' => "laboratory{$index}@example.com"],
                [
                    'full_name' => $laboratoryData['contact_person_name'],
                    'email' => "laboratory{$index}@example.com",
                    'password' => Hash::make('password'),
                    'user_type' => UserType::Laboratory->value,
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            // Create laboratory profile
            Laboratory::updateOrCreate(
                ['laboratory_code' => $laboratoryData['laboratory_code']],
                [
                    'user_id' => $user->id,
                    'laboratory_name' => $laboratoryData['laboratory_name'],
                    'laboratory_code' => $laboratoryData['laboratory_code'],
                    'mobile_number' => $laboratoryData['mobile_number'],
                    'phone_number' => $laboratoryData['phone_number'],
                    'province_id' => $province->id,
                    'city_id' => $city->id,
                    'address' => $laboratoryData['address'],
                    'license_number' => $laboratoryData['license_number'],
                    'contact_person_name' => $laboratoryData['contact_person_name'],
                    'status' => $statuses[array_rand($statuses)],
                ]
            );
        }

        $this->command->info('Laboratories seeded successfully!');
        $this->command->info('Total laboratories: '.Laboratory::count());
    }
}
