<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\City;
use App\Models\Province;
use App\Models\Receiver;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReceiverSeeder extends Seeder
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

        // Receivers with English names
        $englishReceivers = [
            [
                'full_name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'mobile_number' => '0700123500',
                'national_code' => '2000000001',
                'age' => 35,
                'gender' => 'male',
                'blood_type' => 'O',
                'rh_factor' => 'positive',
                'address' => 'Street 10, District 5, Kabul',
            ],
            [
                'full_name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'mobile_number' => '0700123501',
                'national_code' => '2000000002',
                'age' => 28,
                'gender' => 'female',
                'blood_type' => 'A',
                'rh_factor' => 'negative',
                'address' => 'Main Street, Herat City',
            ],
            [
                'full_name' => 'Robert Wilson',
                'email' => 'robert.wilson@example.com',
                'mobile_number' => '0700123502',
                'national_code' => '2000000003',
                'age' => 42,
                'gender' => 'male',
                'blood_type' => 'B',
                'rh_factor' => 'positive',
                'address' => 'Central Avenue, Kandahar',
            ],
        ];

        // Receivers with Persian names
        $persianReceivers = [
            [
                'full_name' => 'حسن محمدی',
                'email' => 'hasan.mohammadi@example.com',
                'mobile_number' => '0700123503',
                'national_code' => '2000000004',
                'age' => 30,
                'gender' => 'male',
                'blood_type' => 'AB',
                'rh_factor' => 'positive',
                'address' => 'خیابان اصلی، شهر کابل',
            ],
            [
                'full_name' => 'مریم حسینی',
                'email' => 'maryam.hosseini@example.com',
                'mobile_number' => '0700123504',
                'national_code' => '2000000005',
                'age' => 25,
                'gender' => 'female',
                'blood_type' => 'O',
                'rh_factor' => 'negative',
                'address' => 'خیابان جمهوریت، هرات',
            ],
            [
                'full_name' => 'علی نوری',
                'email' => 'ali.nouri@example.com',
                'mobile_number' => '0700123505',
                'national_code' => '2000000006',
                'age' => 40,
                'gender' => 'male',
                'blood_type' => 'A',
                'rh_factor' => 'positive',
                'address' => 'بلخ، مزار شریف',
            ],
        ];

        // Receivers with Pashto names
        $pashtoReceivers = [
            [
                'full_name' => 'محمد علی',
                'email' => 'mohammad.ali@example.com',
                'mobile_number' => '0700123506',
                'national_code' => '2000000007',
                'age' => 33,
                'gender' => 'male',
                'blood_type' => 'B',
                'rh_factor' => 'negative',
                'address' => 'د کندهار مرکزي سړک',
            ],
            [
                'full_name' => 'نازیه احمد',
                'email' => 'nazia.ahmad@example.com',
                'mobile_number' => '0700123507',
                'national_code' => '2000000008',
                'age' => 27,
                'gender' => 'female',
                'blood_type' => 'A',
                'rh_factor' => 'positive',
                'address' => 'د جلال آباد سړک',
            ],
            [
                'full_name' => 'عبدالله کریم',
                'email' => 'abdullah.karim@example.com',
                'mobile_number' => '0700123508',
                'national_code' => '2000000009',
                'age' => 38,
                'gender' => 'male',
                'blood_type' => 'O',
                'rh_factor' => 'positive',
                'address' => 'د ننگرهار مرکزي سړک',
            ],
        ];

        $allReceivers = array_merge($englishReceivers, $persianReceivers, $pashtoReceivers);

        foreach ($allReceivers as $index => $receiverData) {
            // Get a random province and city
            $province = $provinces->random();
            $city = $province->cities->random();

            // Create user for receiver
            $user = User::updateOrCreate(
                ['email' => $receiverData['email']],
                [
                    'full_name' => $receiverData['full_name'],
                    'email' => $receiverData['email'],
                    'password' => Hash::make('password'),
                    'user_type' => UserType::Receiver->value,
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            // Create receiver profile
            Receiver::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'mobile_number' => $receiverData['mobile_number'],
                    'national_code' => $receiverData['national_code'],
                    'age' => $receiverData['age'],
                    'gender' => $receiverData['gender'],
                    'province_id' => $province->id,
                    'city_id' => $city->id,
                    'address' => $receiverData['address'],
                    'blood_type' => $receiverData['blood_type'],
                    'rh_factor' => $receiverData['rh_factor'],
                ]
            );
        }

        $this->command->info('Receivers seeded successfully!');
        $this->command->info('Total receivers: '.Receiver::count());
    }
}
