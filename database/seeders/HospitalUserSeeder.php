<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\City;
use App\Models\HospitalUser;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HospitalUserSeeder extends Seeder
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

        // Hospitals with English names
        $englishHospitals = [
            [
                'hospital_name' => 'Kabul Central Hospital',
                'hospital_code' => 'KCH001',
                'contact_person_name' => 'Dr. John Anderson',
                'mobile_number' => '0700200001',
                'phone_number' => '0201234567',
                'license_number' => 'LIC-EN-001',
                'address' => 'Central District, Kabul, Afghanistan',
            ],
            [
                'hospital_name' => 'Herat Medical Center',
                'hospital_code' => 'HMC001',
                'contact_person_name' => 'Dr. Sarah Miller',
                'mobile_number' => '0700200002',
                'phone_number' => '0401234567',
                'license_number' => 'LIC-EN-002',
                'address' => 'Main Street, Herat City',
            ],
            [
                'hospital_name' => 'Kandahar Regional Hospital',
                'hospital_code' => 'KRH001',
                'contact_person_name' => 'Dr. Michael Brown',
                'mobile_number' => '0700200003',
                'phone_number' => '0301234567',
                'license_number' => 'LIC-EN-003',
                'address' => 'Regional Medical District, Kandahar',
            ],
        ];

        // Hospitals with Persian names
        $persianHospitals = [
            [
                'hospital_name' => 'بیمارستان مرکزی کابل',
                'hospital_code' => 'KCH002',
                'contact_person_name' => 'دکتر علی احمدی',
                'mobile_number' => '0700200004',
                'phone_number' => '0201234568',
                'license_number' => 'LIC-FA-001',
                'address' => 'منطقه مرکزی، کابل، افغانستان',
            ],
            [
                'hospital_name' => 'مرکز درمانی هرات',
                'hospital_code' => 'HMC002',
                'contact_person_name' => 'دکتر فاطمه کریمی',
                'mobile_number' => '0700200005',
                'phone_number' => '0401234568',
                'license_number' => 'LIC-FA-002',
                'address' => 'خیابان اصلی، شهر هرات',
            ],
            [
                'hospital_name' => 'بیمارستان مزار شریف',
                'hospital_code' => 'MSH001',
                'contact_person_name' => 'دکتر محمد رضایی',
                'mobile_number' => '0700200006',
                'phone_number' => '0501234567',
                'license_number' => 'LIC-FA-003',
                'address' => 'بلخ، مزار شریف',
            ],
        ];

        // Hospitals with Pashto names
        $pashtoHospitals = [
            [
                'hospital_name' => 'د کابل مرکزي روغتون',
                'hospital_code' => 'KCH003',
                'contact_person_name' => 'ډاکټر احمد خان',
                'mobile_number' => '0700200007',
                'phone_number' => '0201234569',
                'license_number' => 'LIC-PS-001',
                'address' => 'د کابل مرکزي سیمه',
            ],
            [
                'hospital_name' => 'د جلال آباد روغتون',
                'hospital_code' => 'JAH001',
                'contact_person_name' => 'ډاکټر زینب احمد',
                'mobile_number' => '0700200008',
                'phone_number' => '0601234567',
                'license_number' => 'LIC-PS-002',
                'address' => 'د ننگرهار، جلال آباد',
            ],
            [
                'hospital_name' => 'د کندهار روغتون',
                'hospital_code' => 'KAH001',
                'contact_person_name' => 'ډاکټر عمر گل',
                'mobile_number' => '0700200009',
                'phone_number' => '0301234568',
                'license_number' => 'LIC-PS-003',
                'address' => 'د کندهار مرکزي سړک',
            ],
        ];

        $allHospitals = array_merge($englishHospitals, $persianHospitals, $pashtoHospitals);
        $statuses = [0, 1, 2, 3]; // pending, active, inactive, verified

        foreach ($allHospitals as $index => $hospitalData) {
            // Get a random province and city
            $province = $provinces->random();
            $city = $province->cities->random();

            // Create user for hospital
            $user = User::updateOrCreate(
                ['email' => "hospital{$index}@example.com"],
                [
                    'full_name' => $hospitalData['contact_person_name'],
                    'email' => "hospital{$index}@example.com",
                    'password' => Hash::make('password'),
                    'user_type' => UserType::HospitalUser->value,
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            // Create hospital user profile
            HospitalUser::updateOrCreate(
                ['hospital_code' => $hospitalData['hospital_code']],
                [
                    'user_id' => $user->id,
                    'hospital_name' => $hospitalData['hospital_name'],
                    'hospital_code' => $hospitalData['hospital_code'],
                    'mobile_number' => $hospitalData['mobile_number'],
                    'phone_number' => $hospitalData['phone_number'],
                    'province_id' => $province->id,
                    'city_id' => $city->id,
                    'address' => $hospitalData['address'],
                    'license_number' => $hospitalData['license_number'],
                    'contact_person_name' => $hospitalData['contact_person_name'],
                    'status' => $statuses[array_rand($statuses)],
                ]
            );
        }

        $this->command->info('Hospital users seeded successfully!');
        $this->command->info('Total hospital users: '.HospitalUser::count());
    }
}

