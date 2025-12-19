<?php

namespace Database\Seeders;

use App\Enums\BloodInventoryStatus;
use App\Models\BloodDonationRecord;
use App\Models\BloodInventory;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donationRecords = BloodDonationRecord::with('donor')->where('status', 1)->get(); // Only Safe records
        $adminUsers = User::where('is_admin', true)->get();
        $provinces = Province::take(10)->get();

        if ($donationRecords->isEmpty()) {
            $this->command->warn('No safe blood donation records found. Please run BloodDonationRecordSeeder first.');

            return;
        }

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');

            return;
        }

        $statuses = [
            BloodInventoryStatus::InStock->value,
            BloodInventoryStatus::Used->value,
            BloodInventoryStatus::Expired->value,
            BloodInventoryStatus::Discarded->value,
        ];

        // Notes in different languages
        $notes = [
            'Blood bag stored in main inventory. Temperature maintained at 2-6°C.',
            'کیسه خون در انبار اصلی ذخیره شد. دما در 2-6 درجه سانتیگراد حفظ شد.',
            'د وینو کیسه په اصلي انبار کې ساتل شوې. تودوخه په 2-6 درجو سانتي ګراد کې ساتل شوې.',
            'Blood bag removed from inventory for patient use.',
            'کیسه خون از انبار خارج شد برای استفاده بیمار.',
            'د وینو کیسه د ناروغ لپاره د کارولو لپاره له انبار څخه وویستل شوه.',
        ];

        $bagCounter = 1;

        foreach ($donationRecords as $record) {
            // Skip if inventory entry already exists
            if ($record->bloodInventory()->exists()) {
                continue;
            }

            $status = $statuses[array_rand($statuses)];
            $entryDate = $record->donation_date->copy()->addDays(rand(0, 2));
            $expirationDate = $record->expiration_date;
            $exitDate = null;
            $removedBy = null;

            // If status is Used or Expired, set exit date
            if ($status === BloodInventoryStatus::Used->value || $status === BloodInventoryStatus::Expired->value) {
                $exitDate = $entryDate->copy()->addDays(rand(1, 40));
                $removedBy = $adminUsers->random()->id;
            }

            // Generate unique bag ID
            $bagId = 'BAG-'.str_pad((string) $bagCounter, 6, '0', STR_PAD_LEFT).'-'.strtoupper(substr(md5(uniqid()), 0, 4));

            // Get random province for reporting
            $province = $provinces->random();

            BloodInventory::create([
                'bag_id' => $bagId,
                'blood_donation_record_id' => $record->id,
                'province_id' => $province->id,
                'blood_type' => $record->donor->blood_type,
                'rh_factor' => $record->donor->rh_factor,
                'entry_date' => $entryDate,
                'exit_date' => $exitDate,
                'expiration_date' => $expirationDate,
                'status' => $status,
                'added_by' => $adminUsers->random()->id,
                'removed_by' => $removedBy,
                'notes' => $notes[array_rand($notes)],
            ]);

            $bagCounter++;
        }

        $this->command->info('Blood inventory seeded successfully!');
        $this->command->info('Total inventory entries: '.BloodInventory::count());
    }
}

