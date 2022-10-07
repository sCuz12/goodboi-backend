<?php

namespace Database\Seeders;

use App\Enums\LocationsEnum;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (LocationsEnum::Locations as $location) {
            DB::table('locations')->insert([
                'city_id' => $location['city_id'],
                'name'    => $location['name']
            ]);
        }
    }
}
