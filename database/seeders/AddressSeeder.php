<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use App\Models\Purok;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $province = Province::firstOrCreate(
            ['name' => 'Pampanga'],
            ['region' => 'Region III (Central Luzon)']
        );

        $city = City::firstOrCreate([
            'province_id' => $province->id,
            'name'        => 'City of San Fernando',
        ]);

        $barangay = Barangay::firstOrCreate([
            'city_id' => $city->id,
            'name'    => 'Panipuan',
        ]);

        foreach (config('panipone.puroks') as $purokName) {
            Purok::firstOrCreate([
                'barangay_id' => $barangay->id,
                'name'        => $purokName,
            ]);
        }
    }
}
