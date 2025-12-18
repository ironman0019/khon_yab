<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'code' => 'fa',
                'name' => 'Persian',
                'native_name' => 'فارسی',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'code' => 'ps',
                'name' => 'Pashto',
                'native_name' => 'پښتو',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }

        $this->command->info('Languages seeded successfully!');
    }
}
