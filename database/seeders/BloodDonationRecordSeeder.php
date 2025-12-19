<?php

namespace Database\Seeders;

use App\Enums\DonationRecordStatus;
use App\Models\BloodDonationRecord;
use App\Models\City;
use App\Models\Donor;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodDonationRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donors = Donor::with('user')->get();
        $adminUsers = User::where('is_admin', true)->get();

        if ($donors->isEmpty()) {
            $this->command->warn('No donors found. Please run DonorSeeder first.');

            return;
        }

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');

            return;
        }

        $provinces = Province::with('cities')->take(10)->get();

        // Donation types: 0 => Whole Blood, 1 => Plasma, 2 => Platelets
        $donationTypes = [0, 1, 2];
        $statuses = [
            DonationRecordStatus::TestPending->value,
            DonationRecordStatus::Safe->value,
            DonationRecordStatus::Unsafe->value,
            DonationRecordStatus::Discarded->value,
        ];

        // Create donation records for each donor
        foreach ($donors as $donor) {
            $numberOfDonations = rand(1, 5);

            for ($i = 0; $i < $numberOfDonations; $i++) {
                $donationType = $donationTypes[array_rand($donationTypes)];
                $status = $statuses[array_rand($statuses)];

                // Amount based on donation type
                $amount = match ($donationType) {
                    0 => rand(400, 500), // Whole Blood: 400-500ml
                    1 => rand(200, 300), // Plasma: 200-300ml
                    2 => rand(200, 300), // Platelets: 200-300ml
                    default => 450,
                };

                // Donation date (past 1 year to 1 month ago)
                $donationDate = now()->subDays(rand(30, 365));

                // Expiration date based on donation type
                $expirationDate = match ($donationType) {
                    0 => $donationDate->copy()->addDays(42), // Whole Blood: 42 days
                    1 => $donationDate->copy()->addDays(365), // Plasma: 1 year
                    2 => $donationDate->copy()->addDays(5), // Platelets: 5 days
                    default => $donationDate->copy()->addDays(42),
                };

                // Get random province and city
                $province = $provinces->random();
                $city = $province->cities->random();

                // Notes in different languages
                $notes = [
                    'Donation completed successfully. Donor in good health.',
                    'تبرع با موفقیت انجام شد. اهداکننده در سلامت کامل است.',
                    'تبرع په بریالیتوب سره ترسره شو. د اهدا کوونکي روغتیا ښه ده.',
                    'Minor bruising at injection site. No complications.',
                    'کبودی جزئی در محل تزریق. بدون عارضه.',
                    'د انجکشن ځای کې کوچنۍ کبودي. هیڅ پیچلتیا نشته.',
                ];

                BloodDonationRecord::create([
                    'donor_id' => $donor->id,
                    'donation_type' => $donationType,
                    'amount_ml' => $amount,
                    'donation_date' => $donationDate,
                    'expiration_date' => $expirationDate,
                    'status' => $status,
                    'recorded_by_admin' => $adminUsers->random()->id,
                    'submitted_by_donor' => rand(0, 1),
                    'province_id' => $province->id,
                    'city_id' => $city->id,
                    'notes' => $notes[array_rand($notes)],
                ]);
            }
        }

        $this->command->info('Blood donation records seeded successfully!');
        $this->command->info('Total donation records: '.BloodDonationRecord::count());
    }
}

