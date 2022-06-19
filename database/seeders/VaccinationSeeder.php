<?php

namespace Database\Seeders;

use App\Enums\VaccinationsEnum;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (VaccinationsEnum::VACCINATIONS as $vaccination) {
            DB::table('available_vaccinations')->insert([
                'id'  => $vaccination['id'],
                'name' => $vaccination['name'],
                'code' => $vaccination['code'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
