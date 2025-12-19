<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\City;
use App\Models\Donor;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhFactors = ['positive', 'negative'];
        $genders = ['male', 'female'];

        // Get some provinces and cities
        $provinces = Province::with('cities')->take(10)->get();

        if ($provinces->isEmpty()) {
            $this->command->warn('No provinces found. Please run AfghanistanProvinceCitySeeder first.');

            return;
        }

        // Donors with English names
        $englishDonors = [
            [
                'full_name' => 'Michael Johnson',
                'email' => 'michael.johnson@example.com',
                'mobile_number' => '0700123456',
                'national_code' => '1234567890',
                'age' => 28,
                'gender' => 'male',
                'blood_type' => 'O',
                'rh_factor' => 'positive',
                'address' => 'Street 5, District 3, Kabul',
                'health_status' => true,
                'ability_to_donate' => true,
            ],
            [
                'full_name' => 'Sarah Williams',
                'email' => 'sarah.williams@example.com',
                'mobile_number' => '0700123457',
                'national_code' => '1234567891',
                'age' => 32,
                'gender' => 'female',
                'blood_type' => 'A',
                'rh_factor' => 'negative',
                'address' => 'Main Street, Herat City',
                'health_status' => true,
                'ability_to_donate' => true,
            ],
            [
                'full_name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'mobile_number' => '0700123458',
                'national_code' => '1234567892',
                'age' => 45,
                'gender' => 'male',
                'blood_type' => 'B',
                'rh_factor' => 'positive',
                'address' => 'Central Avenue, Kandahar',
                'health_status' => false,
                'ability_to_donate' => false,
            ],
        ];

        // Donors with Persian names
        $persianDonors = [
            [
                'full_name' => 'علی احمدی',
                'email' => 'ali.ahmadi@example.com',
                'mobile_number' => '0700123459',
                'national_code' => '1234567893',
                'age' => 25,
                'gender' => 'male',
                'blood_type' => 'AB',
                'rh_factor' => 'positive',
                'address' => 'خیابان اصلی، شهر کابل',
                'health_status' => true,
                'ability_to_donate' => true,
            ],
            [
                'full_name' => 'فاطمه کریمی',
                'email' => 'fatima.karimi@example.com',
                'mobile_number' => '0700123460',
                'national_code' => '1234567894',
                'age' => 30,
                'gender' => 'female',
                'blood_type' => 'O',
                'rh_factor' => 'negative',
                'address' => 'خیابان جمهوریت، هرات',
                'health_status' => true,
                'ability_to_donate' => true,
            ],
            [
                'full_name' => 'محمد رضایی',
                'email' => 'mohammad.rezaei@example.com',
                'mobile_number' => '0700123461',
                'national_code' => '1234567895',
                'age' => 38,
                'gender' => 'male',
                'blood_type' => 'A',
                'rh_factor' => 'positive',
                'address' => 'بلخ، مزار شریف',
                'health_status' => true,
                'ability_to_donate' => false,
            ],
        ];

        // Donors with Pashto names
        $pashtoDonors = [
            [
                'full_name' => 'احمد خان',
                'email' => 'ahmad.khan@example.com',
                'mobile_number' => '0700123462',
                'national_code' => '1234567896',
                'age' => 27,
                'gender' => 'male',
                'blood_type' => 'B',
                'rh_factor' => 'negative',
                'address' => 'د کندهار مرکزي سړک',
                'health_status' => true,
                'ability_to_donate' => true,
            ],
            [
                'full_name' => 'زینب احمد',
                'email' => 'zainab.ahmad@example.com',
                'mobile_number' => '0700123463',
                'national_code' => '1234567897',
                'age' => 29,
                'gender' => 'female',
                'blood_type' => 'A',
                'rh_factor' => 'positive',
                'address' => 'د جلال آباد سړک',
                'health_status' => true,
                'ability_to_donate' => true,
            ],
            [
                'full_name' => 'عمر گل',
                'email' => 'omar.gul@example.com',
                'mobile_number' => '0700123464',
                'national_code' => '1234567898',
                'age' => 35,
                'gender' => 'male',
                'blood_type' => 'O',
                'rh_factor' => 'positive',
                'address' => 'د ننگرهار مرکزي سړک',
                'health_status' => false,
                'ability_to_donate' => false,
            ],
        ];

        $allDonors = array_merge($englishDonors, $persianDonors, $pashtoDonors);

        foreach ($allDonors as $index => $donorData) {
            // Get a random province and city
            $province = $provinces->random();
            $city = $province->cities->random();

            // Create user for donor
            $user = User::updateOrCreate(
                ['email' => $donorData['email']],
                [
                    'full_name' => $donorData['full_name'],
                    'email' => $donorData['email'],
                    'password' => Hash::make('password'),
                    'user_type' => UserType::Donor->value,
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            // Create donor profile
            Donor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'mobile_number' => $donorData['mobile_number'],
                    'national_code' => $donorData['national_code'],
                    'age' => $donorData['age'],
                    'gender' => $donorData['gender'],
                    'province_id' => $province->id,
                    'city_id' => $city->id,
                    'address' => $donorData['address'],
                    'blood_type' => $donorData['blood_type'],
                    'rh_factor' => $donorData['rh_factor'],
                    'health_status' => $donorData['health_status'],
                    'ability_to_donate' => $donorData['ability_to_donate'],
                    'last_donation_date' => $donorData['ability_to_donate'] && rand(0, 1) ? now()->subDays(rand(30, 180)) : null,
                ]
            );
        }

        $this->command->info('Donors seeded successfully!');
        $this->command->info('Total donors: '.Donor::count());
    }
}

