<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Province;
use Database\Seeders\IranProvinceCitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IranProvinceCitySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_iran_province_city_seeder_creates_all_provinces_and_cities(): void
    {
        $this->seed(IranProvinceCitySeeder::class);

        $this->assertSame(31, Province::count());
        $this->assertGreaterThan(200, City::count());

        $this->assertDatabaseHas('provinces', ['name' => 'تهران']);
        $this->assertDatabaseHas('provinces', ['name' => 'اصفهان']);
        $this->assertDatabaseHas('provinces', ['name' => 'خراسان رضوی']);

        $tehran = Province::query()->where('name', 'تهران')->first();
        $this->assertNotNull($tehran);
        $this->assertTrue(
            City::query()
                ->where('province_id', $tehran->id)
                ->where('name', 'تهران')
                ->exists()
        );

        $isfahan = Province::query()->where('name', 'اصفهان')->first();
        $this->assertNotNull($isfahan);
        $this->assertTrue(
            City::query()
                ->where('province_id', $isfahan->id)
                ->where('name', 'کاشان')
                ->exists()
        );
    }

    public function test_iran_province_city_seeder_is_idempotent(): void
    {
        $this->seed(IranProvinceCitySeeder::class);
        $provinceCount = Province::count();
        $cityCount = City::count();

        $this->seed(IranProvinceCitySeeder::class);

        $this->assertSame($provinceCount, Province::count());
        $this->assertSame($cityCount, City::count());
    }
}
