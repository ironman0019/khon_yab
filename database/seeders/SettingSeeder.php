<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => json_encode([
                    'en' => 'KhonYab Blood Donation System',
                    'fa' => 'سیستم اهدای خون خون یاب',
                    'ps' => 'د وینو اهدا سیسټم خون یاب',
                ]),
                'type' => 'json',
            ],
            [
                'key' => 'site_description',
                'value' => json_encode([
                    'en' => 'A comprehensive blood donation management system for Afghanistan',
                    'fa' => 'سیستم جامع مدیریت اهدای خون برای افغانستان',
                    'ps' => 'د افغانستان لپاره د وینو اهدا د مدیریت جامع سیسټم',
                ]),
                'type' => 'json',
            ],
            [
                'key' => 'default_language_code',
                'value' => 'en',
                'type' => 'string',
            ],
            [
                'key' => 'site_email',
                'value' => 'info@khonyab.af',
                'type' => 'string',
            ],
            [
                'key' => 'site_phone',
                'value' => '+93-700-123-456',
                'type' => 'string',
            ],
            [
                'key' => 'donation_minimum_age',
                'value' => '18',
                'type' => 'integer',
            ],
            [
                'key' => 'donation_maximum_age',
                'value' => '65',
                'type' => 'integer',
            ],
            [
                'key' => 'whole_blood_donation_interval_days',
                'value' => '56',
                'type' => 'integer',
            ],
            [
                'key' => 'plasma_donation_interval_days',
                'value' => '28',
                'type' => 'integer',
            ],
            [
                'key' => 'platelets_donation_interval_days',
                'value' => '7',
                'type' => 'integer',
            ],
            [
                'key' => 'whole_blood_expiration_days',
                'value' => '42',
                'type' => 'integer',
            ],
            [
                'key' => 'plasma_expiration_days',
                'value' => '365',
                'type' => 'integer',
            ],
            [
                'key' => 'platelets_expiration_days',
                'value' => '5',
                'type' => 'integer',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
            ],
            [
                'key' => 'allow_donor_registration',
                'value' => 'true',
                'type' => 'boolean',
            ],
            [
                'key' => 'allow_hospital_registration',
                'value' => 'true',
                'type' => 'boolean',
            ],
            [
                'key' => 'welcome_message',
                'value' => json_encode([
                    'en' => 'Welcome to KhonYab Blood Donation System',
                    'fa' => 'به سیستم اهدای خون خون یاب خوش آمدید',
                    'ps' => 'د وینو اهدا سیسټم خون یاب ته ښه راغلاست',
                ]),
                'type' => 'json',
            ],
            [
                'key' => 'contact_address',
                'value' => json_encode([
                    'en' => 'Kabul, Afghanistan',
                    'fa' => 'کابل، افغانستان',
                    'ps' => 'کابل، افغانستان',
                ]),
                'type' => 'json',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                ]
            );
        }

        $this->command->info('Settings seeded successfully!');
        $this->command->info('Total settings: '.Setting::count());
    }
}

