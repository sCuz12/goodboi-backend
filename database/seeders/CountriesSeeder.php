<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\CountriesEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (CountriesEnum::COUNTRIES as $country) {
            DB::table('countries')->insert([
                'id'  => $country['id'],
                'name' => $country['name'],
                'code' => $country['code'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
