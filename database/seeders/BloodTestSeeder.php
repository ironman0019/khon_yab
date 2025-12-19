<?php

namespace Database\Seeders;

use App\Models\BloodDonationRecord;
use App\Models\BloodTest;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donationRecords = BloodDonationRecord::where('status', '!=', 3)->get(); // Exclude discarded
        $adminUsers = User::where('is_admin', true)->get();

        if ($donationRecords->isEmpty()) {
            $this->command->warn('No blood donation records found. Please run BloodDonationRecordSeeder first.');

            return;
        }

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');

            return;
        }

        // Test logs in different languages
        $testLogs = [
            'All tests completed. Results verified by lab technician.',
            'تمام آزمایشات انجام شد. نتایج توسط تکنسین آزمایشگاه تأیید شد.',
            'ټول ازمایښتونه بشپړ شول. پایلې د لابراتوار تکنسین لخوا تایید شوې.',
            'Tests performed using standard procedures. Equipment calibrated.',
            'آزمایشات با روش‌های استاندارد انجام شد. تجهیزات کالیبره شده است.',
            'ازمایښتونه د معیاري طریقو سره ترسره شول. سامانونه کالیبره شوي.',
        ];

        foreach ($donationRecords as $record) {
            // Skip if test already exists
            if ($record->bloodTest) {
                continue;
            }

            // Random test results (0 = negative, 1 = positive)
            $hivResult = rand(0, 1);
            $hbvResult = rand(0, 1);
            $hcvResult = rand(0, 1);
            $syphilisResult = rand(0, 1);
            $malariaResult = rand(0, 1);

            // Overall result: 0 = safe (all negative), 1 = unsafe (any positive)
            $overallResult = ($hivResult || $hbvResult || $hcvResult || $syphilisResult || $malariaResult) ? 1 : 0;

            // Test date is after donation date but before or on expiration date
            $testDate = $record->donation_date->copy()->addDays(rand(1, 3));

            BloodTest::create([
                'blood_donation_record_id' => $record->id,
                'hiv_result' => $hivResult,
                'hbv_result' => $hbvResult,
                'hcv_result' => $hcvResult,
                'syphilis_result' => $syphilisResult,
                'malaria_result' => $malariaResult,
                'overall_result' => $overallResult,
                'test_date' => $testDate,
                'tested_by' => $adminUsers->random()->id,
                'test_logs' => $testLogs[array_rand($testLogs)],
            ]);
        }

        $this->command->info('Blood tests seeded successfully!');
        $this->command->info('Total blood tests: '.BloodTest::count());
    }
}

