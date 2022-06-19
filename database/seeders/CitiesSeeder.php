<?php

namespace Database\Seeders;

use App\Enums\CitiesEnum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (CitiesEnum::CITIES as $city) {
            DB::table('cities')->insert([
                "id" => $city['id'],
                "country_id" => $city['country_id'],
                "name" => $city['name'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
