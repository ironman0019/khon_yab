<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            TranslationSeeder::class,
            AfghanistanProvinceCitySeeder::class,
            UserSeeder::class,
            DonorSeeder::class,
            HospitalUserSeeder::class,
            BloodDonationRecordSeeder::class,
            BloodTestSeeder::class,
            BloodInventorySeeder::class,
            BloodRequestSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
