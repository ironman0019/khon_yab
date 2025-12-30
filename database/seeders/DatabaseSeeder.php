<?php

namespace Database\Seeders;

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
            ReceiverSeeder::class,
            DonorSeeder::class,
            LaboratorySeeder::class,
            BloodDonationRecordSeeder::class,
            BloodTestSeeder::class,
            BloodInventorySeeder::class,
            BloodRequestSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
